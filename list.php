<style>
    td {
        text-transform: capitalize;
    }
</style>
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Bình Luận</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh bình luận</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <!-- <th>id</th> -->
                            <th>Name</th>
                            <th>Coment</th>
                            <th>Thời Gian</th>
                            <th>Xóa</th>
                            <!-- <th>Delete</th> -->
                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        foreach ($listbinhluan as $i) {
                            extract($i);

                            $xoabl = "index.php?act=xoabl&id=" . $id;
                            echo '
                                <tr>   
                               
                                <td>' . $i['tenuser'] . '</td>
                                <td>' . $i['noidung'] . '</td>
                                <td>' . $i['ngaybinhluan'] . '</td>                             
                                <td>     
                                    <a href="' . $xoabl . '" class="btn btn-danger btn-circle">
                                        <i class="fas fa-trash"></i>
                                    </a> 
                                </td>                
                                </tr>';
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->