<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
if (session()->getFlashData('success')) {
?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
?>
<!-- Hapus debug session diskon agar tidak tampil di UI -->
<!--
<div class="alert alert-warning">
    <strong>DEBUG: Session Diskon Data:</strong>
    <pre><?php print_r(session()->get('diskon')); ?></pre>
</div>
-->
<?php echo form_open('keranjang/edit') ?>
<!-- Table with stripped rows -->
<table class="table datatable">
    <thead>
        <tr>
            <th scope="col">Nama</th>
            <th scope="col">Foto</th>
            <th scope="col">Harga</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Subtotal</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        if (!empty($items)) :
            foreach ($items as $index => $item) :
        ?>
                <tr>
                    <td><?php echo $item['name'] ?></td>
                    <td><img src="<?php echo base_url() . "img/" . $item['options']['foto'] ?>" width="100px"></td>
                    <td>
                        <?php echo number_to_currency($item['options']['harga_asli'], 'IDR') ?><br>
                        <small class="text-success">Diskon: <?php echo number_to_currency($item['options']['diskon'], 'IDR') ?></small><br>
                        <strong><?php echo number_to_currency($item['price'], 'IDR') ?></strong>
                    </td>
                    <td><input type="number" min="1" name="qty<?php echo $i++ ?>" class="form-control" value="<?php echo $item['qty'] ?>"></td>
                    <td>
                        <?php 
                            $subtotalDiskon = ($item['price'] * $item['qty']);
                            echo number_to_currency($subtotalDiskon, 'IDR');
                        ?>
                    </td>
                    <td>
                        <a href="<?php echo base_url('keranjang/delete/' . $item['rowid'] . '') ?>" class="btn btn-danger"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
        <?php
            endforeach;
        endif;
        ?>
    </tbody>
</table>
<!-- End Table with stripped rows -->
<div class="alert alert-info">
    <?php 
        $totalDiskon = 0;
        foreach ($items as $item) {
            $totalDiskon += $item['price'] * $item['qty'];
        }
        echo "Total = " . number_to_currency($totalDiskon, 'IDR');
    ?>
</div>

<button type="submit" class="btn btn-primary">Perbarui Keranjang</button>
<a class="btn btn-warning" href="<?php echo base_url() ?>keranjang/clear">Kosongkan Keranjang</a>
<?php if (!empty($items)) : ?>
    <a class="btn btn-success" href="<?php echo base_url() ?>checkout">Selesai Belanja</a>
<?php endif; ?>
<?php echo form_close() ?>
<?= $this->endSection() ?>
