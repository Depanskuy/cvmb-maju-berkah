<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Feedback;
use App\Models\Partnership;
use App\Models\Project;
use App\Models\User;
use Tests\TestCase;

class BusinessModulesTest extends TestCase
{
    private function createAdmin(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin_test@majusukses.test'],
            [
                'name'     => 'Super Admin Test',
                'password' => bcrypt('password'),
                'role'     => User::ROLE_ADMIN,
            ]
        );
    }

    private function createSeeker(): User
    {
        $user = User::create([
            'name'     => 'Kandidat ' . uniqid(),
            'email'    => 'seeker.' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role'     => User::ROLE_SEEKER,
        ]);

        $user->jobSeekerProfile()->create([
            'phone'     => '081298765432',
            'education' => 'S1 Teknik Sipil',
            'address'   => 'Jakarta',
            'skills'    => 'AutoCAD, SAP2000',
        ]);

        return $user;
    }

    public function test_public_visitor_can_submit_partnership_proposal(): void
    {
        $response = $this->post(route('contact.partnership'), [
            'company_name'     => 'PT Sinar Mandiri Test',
            'contact_name'     => 'Budi Hartono',
            'email'            => 'budi@sinarmandiri.test',
            'phone'            => '081122334455',
            'service_interest' => 'Konstruksi & Renovasi',
            'project_timeline' => 'Q3 2026',
            'project_details'  => 'Kami membutuhkan kontraktor untuk renovasi gedung perkantoran 4 lantai.',
        ]);

        $response->assertSessionHas('success_contact');
        $this->assertDatabaseHas('partnerships', [
            'company_name' => 'PT Sinar Mandiri Test',
            'contact_name' => 'Budi Hartono',
            'status'       => 'pending',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'module' => 'Pengajuan Kerja Sama',
            'action' => 'create',
        ]);
    }

    public function test_admin_can_review_and_update_partnership_status(): void
    {
        $admin = $this->createAdmin();

        $partnership = Partnership::create([
            'company_name' => 'CV Mitra Sejati Test',
            'contact_name' => 'Siti Nurhaliza',
            'email'        => 'siti@mitrasejati.test',
            'phone'        => '085566778899',
            'type'         => 'Mekanikal & Elektrikal',
            'timeline'     => 'Mei 2026',
            'message'      => 'Pengadaan instalasi genset & panel surya.',
            'status'       => 'pending',
        ]);

        $responseView = $this->actingAs($admin)->get(route('admin.kerja-sama.index'));
        $responseView->assertStatus(200);
        $responseView->assertSee('CV Mitra Sejati Test');

        $responseUpdate = $this->actingAs($admin)->patch(route('admin.kerja-sama.status.update', $partnership->id), [
            'status'     => 'approved',
            'admin_note' => 'Dokumen MoU sudah diverifikasi legal.',
        ]);

        $responseUpdate->assertSessionHas('success');
        $this->assertDatabaseHas('partnerships', [
            'id'         => $partnership->id,
            'status'     => 'approved',
            'admin_note' => 'Dokumen MoU sudah diverifikasi legal.',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'module' => 'Pengajuan Kerja Sama',
            'action' => 'status_change',
        ]);
    }

    public function test_admin_can_manage_projects_and_update_field_progress(): void
    {
        $admin = $this->createAdmin();
        $projectName = 'Pembangunan Pabrik ' . uniqid();

        // 1. Tambah project baru
        $responseCreate = $this->actingAs($admin)->post(route('admin.project.store'), [
            'name'        => $projectName,
            'client'      => 'PT Petroindo Sentosa',
            'category'    => 'Konstruksi Bangunan',
            'budget'      => 'Rp 14.5 Miliar',
            'timeline'    => 'Mei 2026 - Des 2026',
            'progress'    => 15,
            'status'      => 'ongoing',
            'description' => 'Tahap pemasangan pondasi tiang pancang.',
        ]);

        $responseCreate->assertSessionHas('success');
        $project = Project::where('name', $projectName)->first();
        $this->assertNotNull($project);
        $this->assertEquals(15, $project->progress);

        // 2. Admin melihat index
        $responseIndex = $this->actingAs($admin)->get(route('admin.project.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee($projectName);

        // 3. Update progres lapangan dari 15% ke 65%
        $responseUpdate = $this->actingAs($admin)->put(route('admin.project.update', $project->id), [
            'name'        => $projectName,
            'client'      => 'PT Petroindo Sentosa',
            'category'    => 'Konstruksi Bangunan',
            'budget'      => 'Rp 14.5 Miliar',
            'timeline'    => 'Mei 2026 - Des 2026',
            'progress'    => 65,
            'status'      => 'ongoing',
            'description' => 'Struktur baja utama telah terpasang.',
        ]);

        $responseUpdate->assertSessionHas('success');
        $project->refresh();
        $this->assertEquals(65, $project->progress);

        $this->assertDatabaseHas('activity_logs', [
            'module' => 'Manajemen Project',
            'action' => 'update',
        ]);

        // 4. Hapus project
        $responseDelete = $this->actingAs($admin)->delete(route('admin.project.destroy', $project->id));
        $responseDelete->assertSessionHas('success');
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_seeker_can_submit_feedback_and_admin_can_manage(): void
    {
        $seeker = $this->createSeeker();
        $admin = $this->createAdmin();

        // 1. Seeker kirim feedback
        $responseSeeker = $this->actingAs($seeker)->post(route('seeker.feedback.store'), [
            'category' => 'Proses Rekrutmen',
            'rating'   => 5,
            'message'  => 'Proses interview sangat ramah dan transparan!',
        ]);

        $responseSeeker->assertSessionHas('success_feedback');
        $feedback = Feedback::where('user_id', $seeker->id)->first();
        $this->assertNotNull($feedback);
        $this->assertEquals(5, $feedback->rating);
        $this->assertEquals('Baru', $feedback->status);

        // 2. Admin view feedback index
        $responseAdmin = $this->actingAs($admin)->get(route('admin.feedback.index'));
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('Proses interview sangat ramah dan transparan!');

        // 3. Admin tandai ditindaklanjuti
        $responseStatus = $this->actingAs($admin)->patch(route('admin.feedback.status.update', $feedback->id));
        $responseStatus->assertSessionHas('success');
        $feedback->refresh();
        $this->assertEquals('Ditindaklanjuti', $feedback->status);

        // 4. Admin hapus feedback
        $responseDestroy = $this->actingAs($admin)->delete(route('admin.feedback.destroy', $feedback->id));
        $responseDestroy->assertSessionHas('success');
        $this->assertDatabaseMissing('feedbacks', ['id' => $feedback->id]);
    }
}
