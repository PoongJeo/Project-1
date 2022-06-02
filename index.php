<?php
session_start();
ob_start();
include "../model/pdo.php";
include "../model/danhmuc.php";
include "../model/product.php";
include "../model/binhluan.php";
// include "./layout.php";



// include "./views/home.php";
// include "./views/footer.php";
include "../model/taikhoan.php";
include "./views/header.php";
include "../model/order.php";
//controller

if (isset($_SESSION['email']) && $_SESSION['email']['role'] == 0 || !isset($_SESSION['email'])) {
    header("location: /PRO1014/Shop/index.php");
    exit();
}


if (isset($_GET['act'])) {
    $act = $_GET['act'];

    switch ($act) {

        case 'add':
            include "danhmuc/add.php";
            break;
        case 'adddm':
            //kiểm tra xem người dùng có click vào nút add hay không
            if (isset($_POST['themmoi'])) {
                $tenloai = $_POST['tenloai'];
                $check_dm=check_dm($tenloai);
                $listdanhmuc = loadall_danhmuc();
                if (!$check_dm) {
                    insert_danhmuc($tenloai);
                    header("location: index.php?act=lisdm");
                } else {
                    echo "<script>
                    alert('Danh mục này đã tồn tại');
                    </script>";
                    include "danhmuc/add.php";
                }
            }
            break;
        case 'lisdm':
            $listdanhmuc = loadall_danhmuc();
            include "danhmuc/list.php";
            break;
        case 'xoadm':
            if (isset($_GET['id']) && ($_GET['id'] > 0)) {
                delete_danhmuc($_GET['id']);
            }
            $listdanhmuc = loadall_danhmuc();
            include "danhmuc/list.php";
            break;
        case 'suadm':
            if (isset($_GET['id']) && ($_GET['id'] > 0)) {
                $dm = loadone_danhmuc($_GET['id']);
            }
            include "danhmuc/update.php";
            break;
        case 'updatedm':
            if (isset($_POST['capnhat'])) {
                $tenloai = $_POST['tenloai'];
                $id = $_GET['id'];
                $dm = loadone_danhmuc($id);
                $check_dm=check_dm($tenloai);
            }if (!$check_dm) {
                update_danhmuc($id, $tenloai);
                header("location: index.php?act=lisdm");
                // $_SESSION['email'] = checkmail($email, $pass);
            } else {
                echo "<script>
                alert('Danh mục này đã tồn tại');
                </script>";
            }
            include "danhmuc/update.php";


            break;


            // controller cho sp

        case 'add_sp':
            include "danhmuc/add.php";
            break;
        case 'addsp':
            //kiểm tra xem người dùng có click vào nút add hay không
            if (isset($_POST['themmoi'])) {
                $iddm = $_POST['iddm'];
                $tensp = $_POST['tensp'];
                $nsx = $_POST['nsx'];
                $giasp = $_POST['giasp'];
                $ram = $_POST['ram'];
                $color = $_POST['color'];
                $mota = $_POST['mota'];
                $hinh = $_FILES['hinh']['name'];
                $target_dir = "../upload/";
                $target_file = $target_dir . basename($_FILES["hinh"]["name"]);
                // $id_sp=insert_sanpham($tensp, $nsx, $giasp, $hinh, $mota, $iddm);
                $checksp=checksp1($tensp);
                if (!$checksp) {
                    // $img = $_FILES['avt']['tmp_name'];
                    $id_sp=insert_sanpham($tensp, $nsx, $giasp, $hinh, $mota, $iddm);
                    insert_img_sanpham($id_sp, $hinh);
                    insert_ram_color($ram, $color, $id_sp);
               
                    
                    // echo $img;
                    // move_uploaded_file($img, 'upload/' . $avt);

                    // $thongbao="ĐẰNG KÝ THÀNH CÔNG";
                    // $_SESSION['dk'] = 'dkk';
                    // include "view/taikhoan/account.php";
                    header("location: index.php?act=listsp");
                } else {
                    echo "<script>
                    alert('Sản phẩm đã tồn tại');
                    </script>";
                }
                // if (move_uploaded_file($_FILES["hinh"]["tmp_name"], $target_file)) {
                //     //  echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
                // } else {
                //     // echo "Sorry, there was an error uploading your file.";
                // }

                
                // $listsanpham=loadall_sanpham();
                
                

            }
            $listdanhmuc = loadall_danhmuc();
            // var_dump($listdanhmuc);
            include "sanpham/add.php";

            break;

        case 'listsp':
            if (isset($_POST['listok']) && ($_POST['listok'])) {
                $kyw = $_POST['kyw'];
                $iddm = $_POST['iddm'];
            } else {
                $kyw = '';
                $iddm = 0;
            }
            $listramandcolor = loadramandcolor();
            $listdanhmuc = loadall_danhmuc();
            $listsanpham = loadall_sanpham($kyw, $iddm);
            include "sanpham/list.php";
            break;
        case 'xoasp':
            if (isset($_GET['id']) && ($_GET['id'] > 0)) {
                delete_ramandcolor($_GET['id']);
                delete_sanpham($_GET['id']);
            }
            $_SESSION['delete_product'] = 'delete_product';

            header("location: index.php?act=listsp");
            break;
        case 'suasp':
            if (isset($_GET['id']) && ($_GET['id'] > 0)) {
                $color = loadone_ramcolor($_GET['id']);
                
            }
            $listdanhmuc = loadall_danhmuc();
            include "sanpham/update.php";
            break;
        case 'updatesp':
            if (isset($_POST['capnhat']) && ($_POST['capnhat'])) {
                $id = $_POST['id'];
                $iddm = $_POST['iddm'];
                $name = $_POST['name'];
                $nsx = $_POST['nsx'];
                $price = $_POST['price'];
                $ram = $_POST['ram'];
                $color = $_POST['color'];
                $stt = $_POST['stt'];
                $mota = $_POST['mota'];
                $hinh = $_FILES['hinh']['name'];
                $target_dir = "../upload/";
                $target_file = $target_dir . basename($_FILES["hinh"]["name"]);
                $img= load_one_img($id);
                $checksp=checksp($name,$id);
                $listdanhmuc = loadall_danhmuc();
                $listsanpham = loadall_sanpham();
                if (!$checksp) {
                    update_sanpham($id, $iddm, $name, $nsx, $price, $mota, $hinh, $stt);
                    update_ramandcolor($ram, $color, $id);
                    header("location: index.php?act=listsp");
                    // $_SESSION['email'] = checkmail($email, $pass);
                } else {
                    echo "<script>
                    alert('Tên sản phẩm đã tồn tại');
                    </script>";

                    include "sanpham/update.php";

                }
                if (move_uploaded_file($_FILES["hinh"]["tmp_name"], $target_file)) {
                } else {
                }
            }
            break;
        case 'addimg':
            //kiểm tra xem người dùng có click vào nút add hay không
            if (isset($_POST['themmoi'])) {
                $id_pro = $_POST['idpro'];
                $hinh = $_FILES['hinh']['name'];
                $target_dir = "../upload/";
                $target_file = $target_dir . basename($_FILES["hinh"]["name"]);
                if (move_uploaded_file($_FILES["hinh"]["tmp_name"], $target_file)) {
                } else {
                }
                insert_img_sanpham($id_pro, $hinh);
                $thongbao = "Thêm thành công ^V^";

                header("location: index.php?act=img_list");
            }
            $listsp = loadall_sp();

            include "sanpham/img.php";

            break;
        case 'img_list':
            $listimng = load_img();
            include "sanpham/listimg.php";
            break;
        case 'suaimg':
            if (isset($_GET['id'])) {
                $img = loadone_img($_GET['id']);
            }
            $listsp = loadall_sp();
            $listimng = load_img();
            include "sanpham/update_img.php";
            break;
        case 'updateimg':

            if (isset($_POST['capnhat']) && ($_POST['capnhat'])) {
                // echo 'vip';
                $id = $_POST['id'];
                $id_pro = $_POST['id_pro'];
                $hinh = $_FILES['hinh']['name'];
                $target_dir = "../upload/";
                $target_file = $target_dir . basename($_FILES["hinh"]["name"]);
                if (move_uploaded_file($_FILES["hinh"]["tmp_name"], $target_file)) {
                } else {
                }
                update_img($id, $id_pro, $hinh);
                $thongbao = "Cập nhật thành công";
            }
            $listimng = load_img();

            include "sanpham/listimg.php";
            break;
        case 'xoaimg':
            if (isset($_GET['id']) && ($_GET['id'] > 0)) {
                delete_img($_GET['id']);
            }
            $listimng = load_img("", 0);
            include "sanpham/listimg.php";
            break;

        case 'order':
            $products = show_order();
            include "order/order.php";
            break;
        case 'xoaorder':
            $id = $_GET['id'];
            $detele = detele($id);
            header("location: index.php?act=order");
            // include "order/order.php";   
            break;
        case 'update_order':
            // $update = update($act);
            if (isset($_POST['btn'])) {
                $act = $_POST['trangthai'];
                $id = $_POST['id'];
                $kq = update($act, $id);
            }
            $products = show_order();
            include "order/order.php";
            break;
        case 'order_detail':
            $id = $_GET['id'];
            $products = show_order_detail($id);
            include "order/order_detail.php";
            break;
        case 'dskh':

            if (isset($_GET['id']) && ($_GET['id'] > 0)) {
                delete_sanpham($_GET['id']);
            }
            $listtaikhoan = loadall_taikhoan();

            include "taikhoan/list.php";
            break;
        case 'dsbl':

            $listbinhluan = loadall_binhluann();
            include "binhluan/list.php";
            break;
        case 'xoabl':
            if (isset($_GET['id']) && ($_GET['id'] > 0)) {
                delete_binhluann($_GET['id']);
            }
            $listbinhluan = loadall_binhluann();
            include "binhluan/list.php";
            break;
        case 'update_tk':
            if (isset($_POST['btn'])) {
                $act = $_POST['settk'];
                $id = $_POST['id'];
                $kq = updatetk($act, $id);
            }
            $listtaikhoan = loadall_taikhoan();
            include "taikhoan/list.php";
            break;
        case 'lienhe':
            $lienhe = show_lienhe();
            include "taikhoan/lienhe.php";
            break;
        case 'xoalh':
            if (isset($_GET['id']) && ($_GET['id'] > 0)) {
                delete_lh($_GET['id']);
            }
            $lienhe = show_lienhe();
            include "taikhoan/lienhe.php";
            break;
        default:
            $show_bieudo = bieudo();
            // $lienhe = show_lienhe();
            $tongproduct = show_tongproduc();
            $tong = show_user();
            $tongdon = show_tongdon();
            $tongtien = show_money();
            $danhsach = product_hot();
            include "./views/home.php";
            break;
    }
} else {
    $show_bieudo = bieudo();
    // $lienhe = show_lienhe();
    $tongproduct = show_tongproduc();
    $tong = show_user();
    $tongdon = show_tongdon();
    $tongtien = show_money();
    $danhsach = product_hot();
    include "./views/home.php";
}
include "./views/footer.php";