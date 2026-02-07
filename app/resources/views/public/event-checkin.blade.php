@extends('layouts.app')
@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Pendaftaran: {{ $event->title }}</h1>
    <form action="{{ route('public.event.store', $event) }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block font-bold">Nomor HP</label>
            <div class="flex gap-2">
                <input type="text" name="phone" id="phone" class="border p-2 w-full rounded" required>
                <button type="button" onclick="cekNomor()" class="bg-blue-600 text-white px-4 py-2 rounded font-bold">Cek</button>
            </div>
        </div>
        <div class="mb-4">
            <label class="block font-bold">Nama Lengkap</label>
            <input type="text" name="name" id="name" class="border p-2 w-full rounded" required>
        </div>
        <div class="mb-4">
            <label class="block font-bold">Alamat</label>
            <textarea name="address" id="address" class="border p-2 w-full rounded" required></textarea>
        </div>
        <button type="submit" class="bg-green-600 text-white p-3 w-full rounded font-bold">Daftar Hadir</button>
    </form>
</div>

<script>
function cekNomor() {
    const phone = document.getElementById('phone').value;
    if(!phone) return alert('Isi nomor HP dulu, Prof!');
    
    fetch("/api/check-phone?phone=" + phone)
        .then(res => res.json())
        .then(data => {
            if(data.status === 'found') {
                document.getElementById('name').value = data.name;
                document.getElementById('address').value = data.address;
                alert('Data ditemukan di Database CRM!');
            } else {
                alert('Nomor baru, silakan isi manual.');
            }
        });
}
</script>
@endsection
