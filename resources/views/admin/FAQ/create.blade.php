@extends('layouts.admin')

@section('content')
    <div class="max-w-xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Tambah FAQ</h1>

        <form action="{{ route('faq.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block font-medium mb-1">Pertanyaan</label>
                <input type="text" name="question" value="{{ old('question') }}" class="w-full border rounded px-3 py-2"
                    required>
                @error('question')
                    <p class="text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-medium mb-1">Jawaban</label>
                <textarea name="answer" rows="4" class="w-full border rounded px-3 py-2" required>{{ old('answer') }}</textarea>
                @error('answer')
                    <p class="text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            <a href="{{ route('faq.index') }}" class="ml-3 text-gray-600 hover:underline">Batal</a>
        </form>
    </div>
@endsection
