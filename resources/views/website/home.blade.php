<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar catatan</title>
</head>
<body>
    <a href="{{ route('create.note') }}">Buat catatan baru</a>
    <br>
    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Judul</th>
                <th>Isi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($note as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $data->title }}</td>
                <td>{{ $data->text }}</td>
                <td>
                    <form action="{{ route('destroy.note') }}">
                        <a href="{{ route('read.note', $data->slug) }}">Lihat</a>
                        <a href="{{ route('edit.note', $data->slug) }}">Edit</a>
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                @empty
                <P>tidak ada catatan</P>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>