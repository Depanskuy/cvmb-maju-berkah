<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSeeker();
    }

    public function rules(): array
    {
        return [
            'applicant_name'    => ['required', 'string', 'max:100'],
            'applicant_email'   => ['required', 'email', 'max:150'],
            'applicant_phone'   => ['required', 'string', 'max:30'],
            'applicant_address' => ['nullable', 'string', 'max:500'],
            'education'         => ['nullable', 'string', 'max:100'],
            'skills'            => ['nullable', 'string', 'max:1000'],
            'cover_letter'      => ['nullable', 'string', 'max:2000'],
            'cv'                => ['required', 'file', 'mimes:pdf', 'max:2048'], // 2MB
        ];
    }

    public function messages(): array
    {
        return [
            'applicant_name.required'  => 'Nama lengkap wajib diisi.',
            'applicant_email.required' => 'Email wajib diisi.',
            'applicant_email.email'    => 'Format email tidak valid.',
            'applicant_phone.required' => 'Nomor WhatsApp wajib diisi.',
            'cv.required'              => 'CV wajib diunggah.',
            'cv.mimes'                 => 'CV harus berformat PDF.',
            'cv.max'                   => 'Ukuran CV maksimal 2 MB.',
        ];
    }
}