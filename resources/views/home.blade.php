@extends('layouts.public')

@section('title', 'CV Maju Berkah — Bertumbuh Bersama, Melangkah Lebih Maju')

@section('content')
    @include('home.hero')
    @include('home.stats')
    @include('home.about')
    @include('home.services')
    @include('home.featured-jobs')
    @include('home.cta')
@endsection