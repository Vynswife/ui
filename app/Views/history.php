<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    
    <!-- Include CSS Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Include Custom CSS -->
    <link href="path/to/your/custom.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .nota {
            width: 100%;
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .nota h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .nota table {
            width: 100%;
            margin-bottom: 20px;
        }
        .nota .table th, .nota .table td {
            text-align: center;
            vertical-align: middle;
        }
        .nota .total {
            font-weight: bold;
        }
        .modal-content {
            border-radius: 10px;
        }
        .modal-header {
            border-bottom: 1px solid #ddd;
            background-color: #f8f9fa;
        }
        .modal-body {
            padding: 20px;
        }
        .modal-footer {
            border-top: 1px solid #ddd;
            background-color: #f8f9fa;
        }
        .modal-title {
            font-size: 18px;
            font-weight: bold;
        }
        .table thead th {
            background-color: #f1f1f1;
        }
        .table td, .table th {
            padding: 10px;
        }
        .btn-details, .btn-finish {
            margin: 0;
            font-size: 16px;
        }
        .btn-details i, .btn-finish i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Nota Heading -->
        <div class="nota">
            <h1>History</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama User</th>
                        <th scope="col">Kode Pembelian</th>
                        <th scope="col">Tanggal Pembelian</th>
                        <th scope="col">Total Harga</th>
                        <th scope="col">Makanan</th>
                        <th scope="col">Status</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($grouped_jel as $kode_transaksi => $transactions): ?>
                        <?php 
                            $total_harga = 0;
                            foreach($transactions as $transaction) {
                                $total_harga += $transaction->total_harga;
                            }
                        ?>
                        <?php foreach($transactions as $index => $kin): ?>
                            <tr>
                                <?php if ($index === 0): ?>
                                    <!-- Hanya menampilkan informasi pada baris pertama dari setiap kelompok kode_transaksi -->
                                    <td rowspan="<?= count($transactions) ?>"><?= $no++ ?></td>
                                    <td rowspan="<?= count($transactions) ?>"><?= $kin->username ?></td>
                                    <td rowspan="<?= count($transactions) ?>"><?= $kin->kode_transaksi ?></td>
                                    <td rowspan="<?= count($transactions) ?>"><?= $kin->tgl_transaksi ?></td>
                                    
                                    <td rowspan="<?= count($transactions) ?>"><?= number_format($total_harga, 2, ',', '.') ?></td>
                                <?php endif; ?>
                                <td><?= $kin->nama ?></td>
                                <td><?= $kin->status ?></td>
                                <?php if ($index === 0): ?>
                                    <td rowspan="<?= count($transactions) ?>">
                                        <!-- Hanya menampilkan tombol Details pada baris pertama -->
                                        <button class="btn btn-danger btn-circle btn-details" data-toggle="modal" data-target="#modalDetail<?= $kode_transaksi ?>">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detail -->
    <?php foreach($grouped_jel as $kode_transaksi => $transactions): ?>
    <div class="modal fade" id="modalDetail<?= $kode_transaksi ?>" tabindex="-1" role="dialog" aria-labelledby="modalDetailTitle<?= $kode_transaksi ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailTitle<?= $kode_transaksi ?>">Detail Transaksi <?= $kode_transaksi ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama User</th>
                                    <th scope="col">Kode Pembelian</th>
                                    <th scope="col">Tanggal Pembelian</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Total Harga</th>
                                    <th scope="col">Makanan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($transactions as $index => $kin): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= $kin->username ?></td>
                                        <td><?= $kin->kode_transaksi ?></td>
                                        <td><?= $kin->tgl_transaksi ?></td>
                                        <td><?= $kin->jumlah ?></td>
                                        <td><?= number_format($kin->total_harga, 2, ',', '.') ?></td>
                                        <td><?= $kin->nama ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                     <a href="<?= base_url('home/printnota/'.$kin->kode_transaksi)?>">
                    <button class="btn btn-secondary"><i class="fas fa-print"></i> Print</button>
                </a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Modal Finish Order -->
    <?php foreach($grouped_jel as $kode_transaksi => $transactions): ?>
    <div class="modal fade" id="modalFinishOrder<?= $kode_transaksi ?>" tabindex="-1" role="dialog" aria-labelledby="modalFinishOrderTitle<?= $kode_transaksi ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFinishOrderTitle<?= $kode_transaksi ?>">Finish Order <?= $kode_transaksi ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to finish this order?</p>
                </div>
                <div class="modal-footer">
                    <form action="<?= base_url('home/finish_order/' . $kode_transaksi) ?>" method="post">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Finish Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Include JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html
