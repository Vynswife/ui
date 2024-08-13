


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan PDF</title>
    <style>
        /* Tambahkan gaya CSS sesuai kebutuhan */
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h2>Laporan PDF</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>tgl transaksi</th>
                <th>kode transaksi</th>
                <th>id makanan</th>
                <th>jumlah</th>
                <th>total harga</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($laporan as $key => $row) : ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $row->tgl_transaksi ?></td>
                    <td><?= $row->kode_transaksi ?></td>
                    <td><?= $row->id_makanan ?></td>
                    <td><?= $row->jumlah ?></td>
                    <td><?= $row->total_harga ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
