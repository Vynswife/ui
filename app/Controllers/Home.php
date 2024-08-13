<?php namespace App\Controllers;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\M_burger;
use App\Models\MakananModel;
use App\Models\KeranjangModel;


class Home extends BaseController
{
    public function __construct()
{
    $this->m_burger = new M_burger();
    if (is_null($this->m_burger)) {
        log_message('error', 'M_burger model is null');
    }
}

    protected $M_burger; // Definisikan properti model
    //standard part
    public function beranda()   
    {
        if(session()->get('level')>0){
        $model= new M_burger;
        $user_id = session()->get('id');
        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
         $where=array(
          'id_toko'=> 1
      );
         $data['setting'] = $model->getWhere('toko',$where);
        echo view('header');
        echo view('menu', $data);
        echo view('beranda', $data);
        $model->logActivity($user_id, 'Beranda', 'User is accessing dashboard page.');
        }else{
            return redirect()->to('http://localhost:8080/home/login');
        }
    }

public function register()
{
    $data = []; // Initialize $data as an empty array
    echo view('header');
    echo view('register', $data);
}

public function aksi_t_register()
{
    $a = $this->request->getPost('nama');
    $b = md5($this->request->getPost('pass'));
    $c = $this->request->getPost('jk');

    $sis = array(
        'level' => '1',
        'username' => $a,
        'pw' => $b,
        'jk' => $c
    );

    $model = new M_burger();
    $model->tambah('user', $sis);
    return redirect()->to('http://localhost:8080/home/login');
}

    public function login()
    {
        echo view('header');
        echo view('login');

    }

    public function aksi_login() {
    $u = $this->request->getPost('username');
    $p = $this->request->getPost('pw');

    $model = new M_burger();
    $where = [
        'username' => $u,
        'pw' => md5($p)
    ];

    $cek = $model->getWhere('user', $where);
    
    if ($cek > 0) {
        session()->set('id', $cek->id_user);
        session()->set('username', $cek->username);
        session()->set('level', $cek->level);
        session()->set('foto', $cek->photo);

        // Log the login activity
        $model->logActivity($cek->id_user, 'login', 'User logged in.');

        return redirect()->to('home/beranda');
    } else {
        return redirect()->to('http://localhost:8080/home/login');
    }
}

public function logout() {
    $user_id = session()->get('id');
    
    if ($user_id) {
        // Log the logout activity
        $model = new M_burger();
        $model->logActivity($user_id, 'logout', 'User logged out.');
    }

    session()->destroy();
    return redirect()->to('http://localhost:8080/home/login');
}



public function aksi_reset($id)
    {
        $model = new M_burger();

        $where= array('id_user'=>$id);

        $isi = array(

            'pw' => md5('0')      

        );
        $model->editpw('user', $isi,$where);

        return redirect()->to('http://localhost:8080/home/user');
        
    }

    public function activity_log() 
{   
    if(session()->get('level')==2){
    $model = new M_burger();
    $logs = $model->getActivityLogs();

    $data['logs'] = $logs;
    $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'

    $where = array(
        'id_toko' => 1
    );
    $data['setting'] = $model->getWhere('toko', $where);

    echo view('header');
    echo view('menu', $data);
    return view('activity_log', $data);
    }else{
            return redirect()->to('http://localhost:8080/home/error_404');
    }
}

public function history_edit() 
{   
    if(session()->get('level')==2){
    $model = new M_burger();
    $user_id = session()->get('id');
    $logs = $model->gethistoryedit();

    $data['logs'] = $logs;
    $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'

    $where = array(
        'id_toko' => 1
    );
    $data['setting'] = $model->getWhere('toko', $where);

    echo view('header');
    echo view('menu', $data);
    return view('history_edit', $data);
    $model->logActivity($user_id, 'Setting', 'User is accessing Setting page.');
}else{
            return redirect()->to('http://localhost:8080/home/error_404');
        }
}

public function restore()
{
    if(session()->get('level')==2 || session()->get('level')==0 ){
        $model= new M_burger;

        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
        // $data['jel']=$model->tampil('makanan');
        $data['jel']=$model->query('select * from makanan where deleted_at IS NOT NULL');
        echo view('header');
        echo view('menu',$data);
        echo view('restore',$data);
        echo view('footer');
    }else{
        return redirect()->to('http://localhost:8080/home/error');
    }
}
public function aksi_restore($id)
{
    $model = new M_burger();

    $where= array('id_makanan'=>$id);
    $isi = array(
        'deleted_at'=>NULL
    );
    $model->edit('makanan', $isi,$where);

    return redirect()->to('home/restore');
}











//form






public function setting()
{
     if (session()->get('level') == 2) {
        $model = new M_burger();
        $user_id = session()->get('id');
        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
         $where=array(
          'id_toko'=> 1
      );
         $data['setting'] = $model->getWhere('toko',$where);
        echo view('header');
        echo view('menu',$data);
        echo view('setting', $data);
        $model->logActivity($user_id, 'Setting', 'User is accessing Setting page.');
        } else {
        return redirect()->to(base_url('http://localhost:8080/home/error_404'));
    }
}
public function aksietoko()
{
    $model = new M_burger();
    $nama = $this->request->getPost('nama');
    $id = $this->request->getPost('id');
    $userId = session()->get('id');

    $uploadedFile = $this->request->getFile('foto');

    $where = array('id_toko' => $id);

    $isi = array(
        'nama_toko' => $nama
    );

    // Cek apakah ada file yang diupload
    if ($uploadedFile && $uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
        $foto = $model->upload1($uploadedFile); // Mengupload file baru dan hapus yang lama
        $isi['logo'] = $foto; // Menambahkan nama file baru ke array data
    }

    $model->edit('toko', $isi, $where);
    $model->history_edit($userId, 'Update Logo and Website name', 'User updated Logo/Website Name.');
    return redirect()->to('home/setting');
}








//menu (udh)

public function t_burger()
{
    if (session()->get('level') == 2) {
        $model= new M_burger;
        $data['jel']= $model->tampil('makanan');
        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
         $where=array(
          'id_toko'=> 1
      );
         $data['setting'] = $model->getWhere('toko',$where);
        echo view('header');
        echo view('menu', $data);
        echo view('t_burger', $data);
    }else{
        return redirect()->to('http://localhost:8080/home/error_404');
    }
}
public function aksi_t_burger()
{
    if (session()->get('level') == 2) {
    $a= $this->request->getPost('nama');
    $b= $this->request->getPost('harga');
    $uploadedFile = $this->request->getfile('foto');
    $foto = $uploadedFile->getName();
    $sis= array(
        'nama'=>$a,
        'harga'=>$b,
        'gambar'=>$foto);
    $model= new M_burger;
    $model->upload($uploadedFile);
    $model->tambah('makanan',$sis);
    return redirect()-> to ('http://localhost:8080/home/keranjang');
    }else{
        return redirect()->to('http://localhost:8080/home/error_404');
    }
}
public function e_burger($id)
{
    if(session()->get('level')>0){
        $model= new M_burger;
        $where= array('id_makanan'=>$id);
        $data['php']=$model->getWhere('makanan',$where);
        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
         $where=array(
          'id_toko'=> 1
      );
         $data['setting'] = $model->getWhere('toko',$where);
        echo view('header');
        echo view('menu', $data);
        echo view('e_burger', $data);
    }else{
        return redirect()->to('http://localhost:8080/home/error_404');
    }
}

public function aksi_e_burger()
{
    $model = new M_burger;
   $a= $this->request->getPost('nama');
    $b= $this->request->getPost('harga');
    $id = $this->request->getPost('id');
    $user_id = session()->get('id');
    $sis= array(
        'nama'=>$a,
        'harga'=>$b,
        'gambar'=>$foto);
    $model= new M_burger;
    $model->upload($uploadedFile);
    $model->edit('makanan', $isi, $where);
    $model->history_edit($id_user, 'Update menu', 'User updated menu.');

    return redirect()->to('http://localhost:8080/home/keranjang');
}

public function h_burger($id)
{
    $model= new M_burger;
    $kil= array('id_makanan'=>$id);
    $model->hapus('makanan',$kil);
    return redirect()-> to('http://localhost:8080/home/keranjang');
}


public function keranjang()
{   
 if(session()->get('level')>0){
    $model= new M_burger();
         $user_id = session()->get('id');
         $where1=array('user.id_user'=>session()->get('id'));

         $data['jel'] = $model->jointigawhere('keranjang','makanan','user','keranjang.id_makanan=makanan.id_makanan','keranjang.id_user=user.id_user','keranjang.id_keranjang', $where1);
         $data['mel']= $model->tampil('makanan');


         $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
         $where=array(
          'id_toko'=> 1
      );
         $data['setting'] = $model->getWhere('toko',$where);
         echo view('header');
         echo view ('menu',$data);
         echo view('keranjang',$data);
     }else{
        return redirect()->to('http://localhost:8080/home/login');

    }
}
protected $m_burger;


    public function aksi_t_keranjang()
{
    $id_makanan = $this->request->getPost('id_makanan');
    $jumlah = $this->request->getPost('jumlah');
    $id_user = $this->request->getPost('id_user');
    $total_harga = $this->request->getPost('total_harga'); // Get total harga

    $data = array(
        'id_makanan' => $id_makanan,
        'jumlah' => $jumlah,
        'id_user' => $id_user,
        'total_harga' => $total_harga // Add total harga
    );

    // Insert data
    if ($this->m_burger->insert($data)) {
        return redirect()->to('/home/keranjang');
    } else {
        // Handle the error
        return redirect()->back()->with('error', 'Failed to add to cart.');
    }
}




public function aksi_e_keranjang()
    {
        $request = \Config\Services::request();
        
        // Get form inputs
        $model= new M_burger;
        $id_keranjang = $request->getPost('id_keranjang');
        $jumlah = $request->getPost('jumlah');
        $user_id = session()->get('id');
        
        // Load models
        $keranjangModel = new KeranjangModel();
        $makananModel = new MakananModel();

        // Get the current item in the cart
        $cartItem = $keranjangModel->find($id_keranjang);

        if ($cartItem) {
            $id_makanan = $cartItem['id_makanan'];

            // Get the product details
            $produk = $makananModel->where('id_makanan', $id_makanan)->first();

            // Check if product exists
            if ($produk) {
                $harga = $produk['harga']; // Get the price of the product

                // Calculate the total price
                $total_harga = $jumlah * $harga;

                // Update the cart item
                $keranjangModel->update($id_keranjang, [
                    'jumlah' => $jumlah,
                    'total_harga' => $total_harga,
                ]);

                $model->history_edit($user_id, 'user', 'User updated an item from cart');

                // Set a success message
                session()->setFlashdata('success', 'Jumlah item berhasil diubah.');
            } else {
                // Set an error message
                session()->setFlashdata('error', 'Produk tidak ditemukan.');
            }
        } else {
            // Set an error message
            session()->setFlashdata('error', 'Item keranjang tidak ditemukan.');
        }

        // Redirect back to the previous page
        return redirect()->back();
    }

public function h_keranjang($id)
{
    $model= new M_burger;
    $kil= array('id_keranjang'=>$id);
    $model->hapus('keranjang',$kil);
    return redirect()-> to('http://localhost:8080/home/keranjang');
}
public function aksi_bayar() {
    $id_user = session()->get('id');
    $paymentMethod = $this->request->getPost('payment_method');
    $address = $this->request->getPost('address');
    $keranjang = $this->request->getPost('keranjang');

    if (empty($paymentMethod) || empty($address) || empty($keranjang)) {
        return redirect()->back()->with('error', 'Metode pembayaran, alamat pengiriman, dan data keranjang harus diisi.');
    }

    $model = new M_burger();
    $keranjangItems = $model->getWherecon('keranjang', ['id_user' => $id_user]);

    if (empty($keranjangItems)) {
        return redirect()->back()->with('error', 'Keranjang kosong!');
    }

    $kode_transaksi = '';

    foreach ($keranjangItems as $item) {
        if (is_object($item)) {
            $id_makanan = $item->id_makanan;
            $jumlah = $item->jumlah;
            $total_harga = $item->total_harga;

            $p1 = date("YmdHms");
            $kode_transaksi = ($p1 . $id_user);

            $dataTransaksi = [
                'tgl_transaksi' => date('Y-m-d H:i:s'),
                'kode_transaksi' => $kode_transaksi,
                'id_user' => $id_user,
                'id_makanan' => $id_makanan,
                'jumlah' => $jumlah,
                'total_harga' => $total_harga,
                'created_by' => $id_user,
            ];

            if (!$model->tambah1('transaksi', $dataTransaksi)) {
                log_message('error', 'Gagal menyimpan data transaksi: ' . json_encode($dataTransaksi));
                return redirect()->back()->with('error', 'Gagal menyimpan data transaksi.');
            }
        }
    }

    if (!$model->hapus('keranjang', ['id_user' => $id_user])) {
        return redirect()->back()->with('error', 'Gagal menghapus data keranjang.');
    }

    // Log the transaction activity
    $model->logActivity($id_user, 'transaction', 'User made a transaction.');

    session()->setFlashdata('success', 'Pesanan sedang diproses.');

    // Redirect to the printnota method with kode_transaksi
    return redirect()->to('home/printnota1/' . $kode_transaksi);
}


public function printnota1($kode_transaksi)
{
    $model = new M_burger();

    if (empty($kode_transaksi)) {
        return redirect()->to('/home')->with('error', 'Kode transaksi tidak valid.');
    }

    $where1 = array('user.id_user' => session()->get('id'));
    $data['jes'] = $model->tampilgambar('toko');
    $where = array('id_toko' => 1);
    $data['setting'] = $model->getWhere('toko', $where);

    $dompdf = new \Dompdf\Dompdf();
    $where2 = array('kode_transaksi' => $kode_transaksi);
    $data['elly'] = $model->jointigawhere('transaksi', 'makanan', 'user', 'transaksi.id_makanan=makanan.id_makanan', 'transaksi.id_user=user.id_user', 'transaksi.kode_transaksi', $where2);

    $html = view('printnota', $data);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A6', 'portrait');
    $dompdf->render();
    $dompdf->stream('laporan_pesanan.pdf', array("Attachment" => false));
}















//pesan



//user (udh)
public function user()
{
    if (session()->get('level') == 2) {
        $model = new M_burger();
        $data['jel'] = $model->tampil('user');

        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
         $where=array(
          'id_toko'=> 1
      );
         $data['setting'] = $model->getWhere('toko',$where);

        echo view('header');
        echo view('menu', $data);
        echo view('user', $data);
    } else {
        return redirect()->to(base_url('home/error_404'));
    }
}

public function aksi_e_user()
{
    $model = new M_burger();

    $id = $this->request->getPost('id_user');
    $username = $this->request->getPost('username');
    $jenis_kelamin = $this->request->getPost('jk');
    $user_id = session()->get('id');

    // Tambahkan log untuk memastikan data diterima
    log_message('info', 'Data diterima: ID=' . $id . ', Username=' . $username . ', JK=' . $jenis_kelamin);

    $where = ['id_user' => $id];
    $data = [
        'username' => $username,
        'jk' => $jenis_kelamin,
    ];

    // Jalankan update
    $model->edit('user', $data, $where);
    $model->history_edit($user_id, 'Update user', 'User updated a user account.');
    return redirect()->to(base_url('home/user'));
}


public function h_user($id)
{
    $model = new M_burger();
    $kil = array('id_user' => $id);
    $model->hapus('user', $kil);
    return redirect()->to(base_url('home/user'));
}


public function t_user()
{
    if (session()->get('level') == 2) {
    $model= new M_burger;
    $data['jel']= $model->tampil('user');
    $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
         $where1=array('user.id_user'=>session()->get('id'));

        // Memuat view
        $where=array(
          'id_toko'=> 1
        );
        $data['setting'] = $model->getWhere('toko',$where);
        echo view('header');
        echo view('menu', $data);
        echo view('t_user', $data);
        } else {
        return redirect()->to(base_url('home/error_404'));
    }
}
public function aksi_t_user()
{
    $a= $this->request->getPost('nama');
    $b= md5($this->request->getPost('pass'));
    $c= $this->request->getPost('jk');
    $u= $this->request->getPost('level');

    $sis= array(
        'level'=>$u,
        'username'=>$a,
        'pw'=>$b,
        'jk'=>$c);
    $model= new M_burger;
    $model->tambah('user',$sis);
    return redirect()-> to ('http://localhost:8080/home/user');
}


public function printnota($kode_transaksi)
{
    // Load model
    $model = new M_burger();
    $where1 = array('user.id_user' => session()->get('id'));

    // Ambil data setting toko
     // Mengambil data dari tabel 'toko'
         $where1=array('user.id_user'=>session()->get('id'));
         $data['jes'] = $model->tampilgambar('toko');
        // Memuat view
        $where=array(
          'id_toko'=> 1
        );
        $data['setting'] = $model->getWhere('toko',$where);

    // Ambil data pesanan berdasarkan kode_pesanan
    $dompdf = new \Dompdf\Dompdf();
    $where2 = array(
        'kode_transaksi' => $kode_transaksi
    );
    $data['elly'] = $model->jointigawhere('transaksi', 'makanan', 'user', 'transaksi.id_makanan=makanan.id_makanan', 'transaksi.id_user=user.id_user', 'transaksi.kode_transaksi', $where2);

    $html = view('printnota', $data);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A6', 'portrait');
    $dompdf->render();
    $dompdf->stream('laporan_pesanan.pdf', array(
        "Attachment" => false
    ));
}


public function data_pembelian()
{
    if(session()->get('level')==2 || session()->get('level')==0 ){
        $model= new M_burger;
        $data['jes']= $model->tampil('form');
        $id = 1; // id_toko yang diinginkan

        // Menyusun kondisi untuk query
        $where = array('id_toko' => $id);

        // Mengambil data dari tabel 'toko' berdasarkan kondisi
        $data['user'] = $model->getWhere('toko', $where);

        // Memuat view
        $where=array(
          'id_toko'=> 1
        );
        $data['setting'] = $model->getWhere('toko',$where);
        echo view('header');
        echo view('menu', $data);
        echo view('error', $data);
    }else{
        return redirect()->to('http://localhost:8080/home/error');
    }
}



public function nota()
{
    if(session()->get('level')>0){
        $model= new M_burger;
        $data['jes']= $model->tampil_join('transaksi','makanan','transaksi.id_makanan=makanan.id_makanan');
        $id = 1; // id_toko yang diinginkan

        // Menyusun kondisi untuk query
        $where = array('id_toko' => $id);

        // Mengambil data dari tabel 'toko' berdasarkan kondisi
        $data['user'] = $model->getWhere('toko', $where);

        // Memuat view
        $where=array(
          'id_toko'=> 1
        );
        $data['setting'] = $model->getWhere('toko',$where);
        echo view('header');
        echo view('menu', $data);
        echo view('nota', $data);
    }else{
        return redirect()->to('http://localhost:8080/home/login');
    }
}



public function history()
{
    // Mengecek level pengguna dari session
    if (session()->get('level') > 0) {
        $model = new M_burger();
        $where1 = array('user.id_user' => session()->get('id'));

        // Mengambil data dari tabel 'toko' dan 'transaksi'
        $data['jes'] = $model->tampilgambar('toko');
        $data['jel'] = $model->jointigawhere('transaksi', 'makanan', 'user', 'transaksi.id_makanan=makanan.id_makanan', 'transaksi.id_user=user.id_user', 'transaksi.id_transaksi', $where1);

        // Mengelompokkan data berdasarkan kode_transaksi
        $grouped_data = [];
        foreach ($data['jel'] as $kin) {
            $grouped_data[$kin->kode_transaksi][] = $kin;
        }

        // Menyimpan data yang sudah digabungkan dalam array baru
        $data['grouped_jel'] = $grouped_data;

        // Menampilkan view
        echo view('header');
        echo view('menu', $data);
        echo view('history', $data);
    } else {
        // Redirect ke halaman login jika level pengguna tidak cukup
        return redirect()->to('/home/login');
    }
}





public function laporan()
    {
        if (session()->get('level') == 2 || session()->get('level') == 0) {
            $model = new M_burger();
             $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
            $id = 1; // id_toko yang diinginkan

        // Menyusun kondisi untuk query
        $where = array('id_toko' => $id);

        // Mengambil data dari tabel 'toko' berdasarkan kondisi
        $data['user'] = $model->getWhere('toko', $where);

        // Memuat view
        $where=array(
          'id_toko'=> 1
        );
        $data['setting'] = $model->getWhere('toko',$where);
        echo view('header');
        echo view('menu', $data);
        echo view('laporan', $data);
        } else {
            return redirect()->to('http://localhost:8080/home/error');
        }
    }
    public function print_pdf($kode_transaksi)
    {
        $model = new M_burger();
        $where = ['transaksi.kode_transaksi' => $kode_transaksi];
        $transactions = $model->joinnota('transaksi', 'makanan', 'transaksi.id_makanan = makanan.id_makanan', $where);

        if (empty($transactions)) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }

        $data['transactions'] = $transactions;

        // Load the PDF library
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('pdf_view', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('transaction_' . $kode_transaksi . '.pdf', array("Attachment" => 0));
    }
    public function generate_pdf()
{
    if (session()->get('level') > 0) {
        $this->laporan_pdf();
    } else {
        return redirect()->to('home/login');
    }
}

private function laporan_pdf()
{
    $model = new M_burger();

    $start_date = $this->request->getPost('start_date'); 
    $end_date = $this->request->getPost('end_date'); 

    $data['laporan'] = $model->getLaporanByDate($start_date, $end_date); 
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    $html = view('laporan_pdf', $data);

    $dompdf->loadHtml($html);
    $dompdf->render();
    $dompdf->stream("laporan.pdf", array("Attachment" => false)); // Change to false to open in browser
}

public function generate_excel()
{
    $model = new M_burger();
    $start_date = $this->request->getPost('start_date'); 
    $end_date = $this->request->getPost('end_date'); 

    $data['laporan'] = $model->getLaporanByDateForExcel($start_date, $end_date);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'ID Transaksi')
          ->setCellValue('B1', 'Tanggal Transaksi')
          ->setCellValue('C1', 'Kode Transaksi')
          ->setCellValue('D1', 'ID Makanan')
          ->setCellValue('E1', 'Jumlah')
          ->setCellValue('F1', 'Total Harga');

    $rowCount = 2;
    foreach ($data['laporan'] as $row) {
        $sheet->setCellValue('A'.$rowCount, $row['id_transaksi'])
              ->setCellValue('B'.$rowCount, $row['tgl_transaksi'])
              ->setCellValue('C'.$rowCount, $row['kode_transaksi'])
              ->setCellValue('D'.$rowCount, $row['id_makanan'])
              ->setCellValue('E'.$rowCount, $row['jumlah'])
              ->setCellValue('F'.$rowCount, $row['total_harga']);
        $rowCount++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="laporan_transaksi.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
}


public function generate_window()
{
    if (session()->get('level') > 0) {
        echo view('header');
        echo view('menu');
        echo view('laporan');
        echo view('footer');
    } else {
        return redirect()->to('home/login');
    }
}

public function generate_window_result()
{
    if (session()->get('level') > 0) {
        // Ambil data formulir berdasarkan rentang waktu dari request POST
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');
        $model = new M_burger();
        $data['formulir'] = $model->getLaporanByDate($start_date, $end_date);

        echo view('cetak_hasil', $data);
    } else {
        return redirect()->to('home/login');
    }
}

public function profile($id)   
{
    if (session()->get('level') > 0) {
        $model = new M_burger();

        // Load user data based on the provided id
        $where= array('id_user'=>$id);

        $data['user']=$model->getWhere('user',$where);

        // Load other necessary data
        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
        $data['setting'] = $model->getWhere('toko', ['id_toko' => 1]);

        // Load the views with the data
        echo view('header');
        echo view('menu', $data);
        echo view('profile', $data);
        echo view('footer');
    } else {
        return redirect()->to('http://localhost:8080/home/login');
    }
}



public function aksi_e_profile()
{
    $model = new M_burger;

    // Ambil data dari form
    $username = $this->request->getPost('username');
    $gender = $this->request->getPost('jk');
    $uploadedFile = $this->request->getFile('foto');
    $userId = $this->request->getPost('id');


    // Cek apakah ada file yang di-upload
    if ($uploadedFile && $uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
        $fileName = $uploadedFile->getName();
        $model->upload($uploadedFile);
    } else {
        // Debug: Ambil data user dari database dan pastikan data tersedia
        $existingUser = $model->where('id_user', $userId)->first();
        
        if (!$existingUser) {
            // Jika data user tidak ditemukan
            log_message('error', 'User not found with ID: ' . $userId);
            session()->setFlashdata('error', 'User not found.');
            return redirect()->back();
        }

        $fileName = $existingUser['foto'] ?? ''; // Ambil data foto dari hasil query
    }

    // Data yang akan diperbarui
    $dataToUpdate = [
        'username' => $username,
        'jk' => $gender,
        'foto' => $fileName
    ];

    // Debugging untuk melihat apakah dataToUpdate diisi dengan benar
    log_message('info', 'Data to update: ' . json_encode($dataToUpdate));

    // Kondisi untuk update
    $where = ['id_user' => $userId];

    // Update profil pengguna
    if (!$model->edit('user', $dataToUpdate, $where)) {
        log_message('error', 'Failed to update user profile: ' . json_encode($dataToUpdate));
        session()->setFlashdata('error', 'Failed to update profile.');
        return redirect()->back();
    }

    // Catat aktivitas setelah profil diperbarui
    $model->history_edit($userId, 'Update profile', 'User updated their profile.');

    // // Redirect ke halaman profil dengan pesan sukses
    // session()->setFlashdata('success', 'Profile updated successfully.');
    return redirect()->to(base_url('home/profile/' . $userId));
}




// public function aksi_e_profile()
// {
//     $model= new M_burger;
//     $a= $this->request->getPost('nama');
//     $b= $this->request->getPost('jenis');
//     $id=$this->request->getPost('id_user');
//     $uploadedFile = $this->request->getfile('foto');
//     $foto = $uploadedFile->getName();
//     $where = array('id_user'=>$id);
//     $isi= array(
//         'username'=>$a,
//         'jk'=>$b,
//         'foto'=>$foto);
//     $model->edit('user', $isi, $where);
//     return redirect()->to('/home/profile/' . $id);
// }









//menu (udh)
public function aksi_e_pesanan()
{
    $model = new M_burger;
    $status = $this->request->getPost('status');
    $kodeTransaksi = $this->request->getPost('kode_transaksi');

    // Update status untuk semua transaksi dengan kode_transaksi yang sama
    $where = array('kode_transaksi' => $kodeTransaksi);
    $data = array('status' => $status);
    $model->edit('transaksi', $data, $where);

    // Kembalikan JSON response untuk AJAX
    return $this->response->setJSON(['status' => true, 'new_status' => $status]);
}

public function e_pesanan($id)
{
    if(session()->get('level')>0){
        $model= new M_burger;
        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
        $where= array('id_transaksi'=>$id);
        $data['php']=$model->getWhere('transaksi',$where);
        echo view('header');
        echo view('menu', $data);
        echo view('e_pesanan',$data);
        echo view('footer');
    }else{
        return redirect()->to('http://localhost:8080/home/login');
    }
}

public function pesanan()
{
    if(session()->get('level') > 0) {
        $model = new M_burger;
        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
        
        // Fetch and group data by kode_transaksi
        $data['jel'] = $model->joinAndGroupByTransaction();
        $grouped_data = [];
        foreach ($data['jel'] as $kin) {
            $grouped_data[$kin->kode_transaksi][] = $kin;
        }
        $data['grouped_jel'] = $grouped_data;

        echo view('header');
        echo view('menu', $data);
        echo view('pesanan1', $data);
    } else {
        return redirect()->to('http://localhost:8080/home/login');
    }
}
}