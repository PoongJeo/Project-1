
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thêm danh mục</h1>
    </div>
<form action="index.php?act=adddm" method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="name_categories" class="form-label">Tên danh mục</label>
    <input type="text" class="form-control" id="name_categories" name="tenloai">
  </div>
  <div class="mb-3">
  <!-- <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gridCheck" value="1" name="Hot">
      <label class="form-check-label" for="gridCheck">
        Hot
      </label>
    </div>
  </div> -->
  <button type="submit" name="themmoi" class="btn btn-primary">Submit</button>
</form>

</div>
<!-- <div class="row">
            <div class="row frmtitle">
                <H1>THÊM MỚI LOẠI HÀNG HÓA</H1>
            </div>
            <div class="row frmcontent">
                <form action="index.php?act=adddm" method="post">
                    <div class="row mb10">
                        Mã loại<br>
                        <input type="text" name="maloai" disabled>
                    </div>
                    <div class="row mb10">
                        Tên loại<br>
                        <input type="text" name="tenloai">
                    </div>
                    <div class="row mb10">
                        <input type="submit" name="themmoi" value="THÊM MỚI">
                        <input type="reset" value="NHẬP LẠI">
                        <a href="index.php?act=lisdm"><input type="button" value="DANH SÁCH"></a>
                    </div>
                    <?php
                        if(isset($thongbao)&&($thongbao!="")) echo $thongbao;
                    ?>
    
                </form>
            </div>
        </div>
    </div> -->
   
    

    