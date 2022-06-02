<?php
session_start();
ob_start();
define('_DIR_ROOT', __DIR__);
// session_destroy();
include "./model/pdo.php";
include "./model/product.php";
include "./model/taikhoan.php";
include "./model/binhluan.php";
include "./model/cart.php";
include "./model/danhmuc.php";
include "./model/order.php";
require_once 'config.php';
include "global.php";
$listdanhmuc = full_dm();
include "./view/header.php";
// $listdanhmuc_shop =full_dm();
if (!isset($_SESSION['mycart'])) $_SESSION['mycart'] = [];
$product_show = show_product_home();
$show_product_new = new_product();
$hot_product = hot_product();
$hot_pk =  hot_pk();
$all_all_product = all_all_product();
$all_product = product_cate();
$all_product2 = product_cate2();
$all_product3 =  product_cate3();
$all_product4 =  product_cate4();
$all_product5 =  product_cate5();
$all_product6 =  product_cate6();
$all_product7 =  product_cate7();


if (isset($_GET['act']) && ($_GET['act'] != "")) {
    $act = $_GET['act'];
    switch ($act) {
        case 'shop':

            $show_pro_cate = load_sp_cate($_GET['id']);
            $product_show = show_product_home();

            include "view/sanpham/shop.php";
            break;
        case 'shopp':
            $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
            $total_records = tongRecord();
            $limit = 6;
            $total_page = ceil($total_records / $limit);
            // var_dump($total_records);
            $start = ($current_page - 1) * $limit;
            if ($current_page > $total_page) {
                $current_page = $total_page;
            } else if ($current_page < 1) {
                $current_page = 1;
            }
            $show_product_phantrang = phantrang($start, $limit);
            // $product_show= show_product_home();
            include "view/sanpham/shopp.php";
            break;

        case 'product':
            // unset($_SESSION['mycart']);
            if (isset($_GET['id']) && ($_GET['id'] > 0)) {
                $id = $_GET['id'];
                $iddm = $_GET['iddm'];
                $show_detail = loadone_product($id);
                $img = show_img($id);
                tangSoLanXem($id);
                $color = loadcolor($id);
                $ram = loadram($id);
                $tongbl = show_tongcmt($id);
                $show_cmt = loadall_binhluan($id);
                $showslide = show_slide($iddm, $id);
                //$comment = cmt($tenuser,$noidung,$idpro);
                // extract($show_detail);
                // $productcungloai=load_product_cungloai($id_product,$id_category_product);
                include "view/sanpham/product.php";
            } else {
                echo 'gà';
            }
            $showslide = show_slide($iddm, $id);
            break;
        case 'comment':
            if (isset($_POST['guibinhluan'])) {
                insert_binhluan($_POST['binhluan'], $_GET['id_user'], $_GET['id'], $_POST['ten']);
                //loi
                $id = $_GET['id'];
                $iddm = $_GET['iddm'];
                $show_detail = loadone_product($id);
                $img = show_img($id);
                tangSoLanXem($id);
                $color = loadcolor($id);
                $ram = loadram($id);
            }

            header("location: index.php?act=product&id=$id&iddm=$iddm");
            break;
        case 'addtocart':
            // $_SESSION['soluong'] = $_POST['qty'];
            //   var_dump($_POST);
            if (isset($_POST['qty'])) {
                $soluong = $_POST['qty'];
            } else {
                $soluong = 1;
            }
            $productId = $_GET['id'] ?? null;
            $product = product($productId);
            if (empty($_SESSION['mycart']) || !array_key_exists($productId, $_SESSION['mycart'])) {
                $_SESSION['mycart'][$productId]['qty'] = $soluong;
                $_SESSION['mycart'][$productId]['id'] = $productId;
                $_SESSION['mycart'][$productId]['img'] = $product['img'];
                $_SESSION['mycart'][$productId]['price'] = $_POST['price'];
                $_SESSION['mycart'][$productId]['name'] = $_POST['name'] . " - " . $_POST['color'] . " - " . $_POST['ram'];
            } else {
                $product['qty'] = $_SESSION['mycart'][$productId]['qty'] + $soluong;
                $_SESSION['mycart'][$productId] = $product;
            }

            // $product['qty'] = $_POST['qty'];
            if (!isset($_SESSION['mycart'])) {
                $_SESSION['mycart'] = null;
            }
            $product = $_SESSION['mycart'];
            // var_dump($product);
            header("location: index.php?act=viewcart");
            break;
            // case 'tang_sl':
            //     if(isset($_POST('id'))){

            //     }
            //     break;

            // case 'delcartt':
            //             if(isset($_GET['idcart'])){
            //                 array_splice($_SESSION['mycart'],$_GET['idcart'],1);
            //             }else{
            //                 $_SESSION['mycart']=[];
            //             }
            //             header('Location:index.php?act=viewcart');
            //             exit();
            //             break;
        case 'delcart':

            $productId = $_GET['idcart'] ?? null;
            unset($_SESSION['mycart'][$productId]);
            header('location: index.php?act=viewcart');
        case 'viewcart':

            if (!isset($_SESSION['mycart'])) {
                $_SESSION['mycart'] = null;
            }
            $product = $_SESSION['mycart'];
            include "view/cart/viewcart.php";
            break;
        case 'updatecart':
            // $_SESSION['soluong'] = $_POST['qty'];
            // var_dump($_GET);
            $soluong = $_GET['qty'];
            foreach ($soluong as $key => $sl) {

                if (array_key_exists($key, $_SESSION['mycart'])) {
                    $_SESSION['mycart'][$key]['qty'] =  $sl['qty'];
                }
            }
            // exit();
            // var_dump($product);
            header("location: index.php?act=viewcart");
            break;
        case 'checkout':
            //    $id_User = $_SESSION['email']['id'];
            if (!isset($_SESSION['mycart'])) {
                $_SESSION['mycart'] = null;
            }

            $product = $_SESSION['mycart'];
            include "view/sanpham/checkout.php";
            break;
        case 'view_order':
            $id_User = $_SESSION['email']['id'];
            $productss = show_order_view($id_User);
            include "view/taikhoan/order.php";
            break;
        case 'view_order_detail':
            $id = $_GET['id'];
            $products = show_order_detail_user($id);
            // var_dump($products);
            include "view/taikhoan/vieworder.php";
            break;

        case 'search':
            if (isset($_POST['listok']) && ($_POST['listok'])) {
                $kyw = $_POST['kyw'];;
            } else {
                $kyw = '';
            }
            $listsanpham = loadall_sanpham($kyw);
            include "view/sanpham/shoppp.php";
            break;
        case 'single-blog':
            include "view/sanpham/single-blog.php";
            break;

        case 'about':
            include "view/lienhe/about.php";
            break;
        case 'contact':
            include "view/lienhe/contact.php";
            break;

            //người dùng       
        case 'account':
            include "view/taikhoan/account.php";
            break;
            /* case 'register':
            include "view/taikhoan/register.php";
            break; */
            /* case 'login':
            include "view/taikhoan/login.php";
            break; */
        case 'forgot-password':
            include "view/taikhoan/forgot-password.php";
            break;


        case 'dangky':

            if (isset($accessToken)) {
                if (!isset($_SESSION['facebook_access_token'])) {
                    //get short-lived access token
                    $_SESSION['facebook_access_token'] = (string) $accessToken;

                    //OAuth 2.0 client handler
                    $oAuth2Client = $fb->getOAuth2Client();

                    //Exchanges a short-lived access token for a long-lived one
                    $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
                    $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

                    //setting default access token to be used in script
                    $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
                } else {
                    $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
                }
                //redirect the user to the index page if it has $_GET['code']
                // if (isset($_GET['code'])) {
                //     header('Location: ./');
                // }
                try {
                    $fb_response = $fb->get('/me?fields=name,first_name,last_name,email');
                    $fb_response_picture = $fb->get('/me/picture?redirect=false&height=200');

                    $fb_user = $fb_response->getGraphUser();
                    $picture = $fb_response_picture->getGraphUser();

                    $_SESSION['fb_user_id'] = $fb_user->getProperty('id');
                    $_SESSION['fb_user_name'] = $fb_user->getProperty('name');
                    $_SESSION['fb_user_email'] = $fb_user->getProperty('email');
                    $_SESSION['fb_user_pic'] = $picture['url'];
                    $_SESSION['fb_user_name'] = trim(strip_tags($_SESSION['fb_user_name']));
                } catch (Facebook\Exceptions\FacebookResponseException $e) {
                    echo 'Facebook API Error: ' . $e->getMessage();
                    session_destroy();
                    // redirecting user back to app login page
                    header("Location: ./");
                    exit;
                } catch (Facebook\Exceptions\FacebookSDKException $e) {
                    echo 'Facebook SDK Error: ' . $e->getMessage();
                    exit;
                }
            }
            if (isset($_SESSION['facebook_access_token'])) {
                $check_login_face = check_login_face(trim(strip_tags($_SESSION['fb_user_id'])));
                if (is_array($check_login_face)) {
                    $_SESSION['tc'] = 'ok';
                    $_SESSION['email'] = $check_login_face;
                }
            };
            if (isset($_SESSION['logingg'])) {
                $check_login_gg = check_login_gg(trim(strip_tags($_SESSION['logingg']['id'])));
                if (is_array($check_login_gg)) {
                    $_SESSION['tc'] = 'ok';
                    $_SESSION['email'] = $check_login_gg;
                    unset($_SESSION['logingg']);
                }
            };
            if (isset($_POST['dangky']) && ($_POST["dangky"])) {
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $pass = $_POST['pass'];
                $email = $_POST['email'];
                $address = $_POST['address'];
                $tell = $_POST['tell'];
                $avt = $_POST['avt'];
                $id_face = $_POST['id_face'];
                $id_gg = $_POST['id_gg'];
                // $img = $_FILES['avt']['tmp_name'];
                $chekcmail = checkemail($email);
                if (!$chekcmail) {
                    // $img = $_FILES['avt']['tmp_name'];
                    insert_taikhoan($firstname, $lastname, $pass, $email, $address, $tell, $avt, $id_face, $id_gg);
                    // echo $img;
                    // move_uploaded_file($img, 'upload/' . $avt);

                    // $thongbao="ĐẰNG KÝ THÀNH CÔNG";
                    $_SESSION['dk'] = 'dkk';
                    include "view/taikhoan/account.php";
                } else {
                    echo "<script>
                    alert('Mail đã tồn tại');
                    </script>";
                }
            }
            if (isset($_SESSION['email'])) {
                header("location: index.php?act=account");
            }
            include "view/taikhoan/register.php";
            break;
        case 'lienhe':
            if (isset($_POST['lienhe']) && ($_POST["lienhe"])) {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $chude = $_POST['chude'];
                $loinhan = $_POST['loinhan'];
                if (isset($_POST['id'])) {
                    $id = $_POST['id'];
                } else {
                    $id = 0;
                }
                insert_lienhe($id, $name, $email, $chude, $loinhan);
                header("location: index.php?act=contact");
            }
            break;
        case 'logingoogle':
            if (isset($_POST['id'])) {
                $_SESSION['logingg'] = ['id' => $_POST['id'], 'name' => $_POST['name'], 'img' => $_POST['img'], 'mail' => $_POST['mail']];
                echo 'thanhcong';
            }
            echo 'thanhcong';
            break;
        case 'dangnhap':
            if (isset($_POST['dangnhap']) && ($_POST["dangnhap"])) {
                $email = $_POST['email'];
                $pass = $_POST['pass'];
                $checkmail = checkmail($email, $pass);
                if (is_array($checkmail)) {
                    $_SESSION['tc'] = 'ok';
                    $_SESSION['email'] = $checkmail;
                } else {
                    $_SESSION['tb'] = 'ko';
                    // $thongbao="Tài khoản không tồn tại. Vui lòng kiểm tra lại";
                }
                echo header("refresh: 1");
            }
            include "view/taikhoan/account.php";
            break;
        case 'edit_taikhoan':
            if (isset($_POST['capnhat']) && ($_POST["capnhat"])) {
                $id = $_POST['id'];
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $pass = $_POST['pass'];
                $email = $_POST['email'];
                $address = $_POST['address'];
                $tell = $_POST['tell'];
                $chekcmail = checkemail2($email, $id);
                if (!$chekcmail) {
                    update_taikhoan($id, $firstname, $lastname, $pass, $email, $address, $tell);
                    $_SESSION['email'] = checkmail($email, $pass);
                    header("location: index.php?act=account");
                } else {
                    echo "<script>
                alert('Mail đã tồn tại');
                </script>";
                }
            }
            include "view/taikhoan/edit_taikhoan.php";

            break;
        case 'thoat':
            session_unset();
            $permissions = ['email']; //optional
            // $gg=['email']; 
            $fb_login_url = $fb_helper->getLoginUrl('https://localhost/PRO1014/Shop/index.php?act=dangky', $permissions);
            // $authUrl=$client->createAuthUrl();
            // echo $fb_login_url;
            // echo header("refresh: 1");
            include "view/taikhoan/login.php";
            break;
        case 'quenmk':
            include "view/taikhoan/forgot-password.php";
            break;


        case 'bill':
            include "view/cart/bill.php";
            break;
            // case 'test':
            //     $thongtin = urlencode("Tên Phiên Emailtrandachuyyy48@gmail.com Địa chỉaaaa Phone0123456789 Sản phẩm iPhone 13 Pro Max - black - 8gb/256gb x 1 Tổng33990000");
            //     echo "https://api.telegram.org/bot2123578038:AAGBFpDM9rG6uYDSBlkwq9qoGH3Yx5NOkVs/sendMessage?parse_mode=html&chat_id=-716594182&text=" . $thongtin;
            //     file_get_contents("http://api.telegram.org/bot2123578038:AAGBFpDM9rG6uYDSBlkwq9qoGH3Yx5NOkVs/sendMessage?parse_mode=html&chat_id=-716594182&text=" . $thongtin);
            //     break;
        case 'billconfirm':


            if (isset($_POST['dongydathang']) || isset($_POST['thanhtoanonline'])) {
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $email = $_POST['email'];
                $address = $_POST['address'];
                // $ngaydathang=date('h:i:sa d/m/Y');
                $tell = $_POST['tell'];
                $tongdonhang = isset($_SESSION['tongdonhang']) ? $_SESSION['tongdonhang'] : 0;
                $note = $_POST['note'];
                $id_User = $_SESSION['email']['id'];
                // $bill_detail =insert_cart($id_User,$idpro,$img,$name,$price,$soluong,$thanhtien,$idbill);
                $idbill = insert_bill($id_User, $firstname, $lastname, $email, $address, $tell, $tongdonhang, $note);
                // unset($_SESSION['tongdonhang']);
                if (isset($_POST['thanhtoanonline'])) {
                    require_once("./vnpay_php/config.php");
                    $vnp_TxnRef = $idbill; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
                    $vnp_OrderInfo = "thanh toán đơn hàng" . $idbill;


                    $vnp_Amount = $tongdonhang * 100;
                    $vnp_Locale = "vn";
                    $vnp_BankCode = "NCB";
                    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
                    //Add Params of 2.0.1 Version
                    // $vnp_ExpireDate = $_POST['txtexpire'];
                    //Billing
                    $vnp_Bill_Mobile = $tell;
                    $vnp_Bill_Email =  $email;
                    $vnp_Bill_FirstName = $firstname;
                    $vnp_Bill_LastName = $lastname;

                    // $vnp_Bill_Address = $address;
                    // $vnp_Bill_City = $_POST['txt_bill_city'];
                    // $vnp_Bill_Country = $_POST['txt_bill_country'];
                    // $vnp_Bill_State = $_POST['txt_bill_state'];
                    // Invoice
                    // $vnp_Inv_Phone = $_POST['txt_inv_mobile'];
                    // $vnp_Inv_Email = $_POST['txt_inv_email'];
                    // $vnp_Inv_Customer = $_POST['txt_inv_customer'];
                    // $vnp_Inv_Address = $_POST['txt_inv_addr1'];
                    // $vnp_Inv_Company = $_POST['txt_inv_company'];
                    // $vnp_Inv_Taxcode = $_POST['txt_inv_taxcode'];
                    // $vnp_Inv_Type = $_POST['cbo_inv_type'];
                    $inputData = array(
                        "vnp_Version" => "2.1.0",
                        "vnp_TmnCode" => $vnp_TmnCode,
                        "vnp_Amount" => $vnp_Amount,
                        "vnp_Command" => "pay",
                        "vnp_CreateDate" => date('YmdHis'),
                        "vnp_CurrCode" => "VND",
                        "vnp_IpAddr" => $vnp_IpAddr,
                        "vnp_Locale" => $vnp_Locale,
                        "vnp_OrderInfo" => $vnp_OrderInfo,

                        "vnp_ReturnUrl" => $vnp_Returnurl,
                        "vnp_TxnRef" => $vnp_TxnRef,
                        // "vnp_ExpireDate" => $vnp_ExpireDate,
                        "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
                        "vnp_Bill_Email" => $vnp_Bill_Email,
                        "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
                        "vnp_Bill_LastName" => $vnp_Bill_LastName,
                        // "vnp_Bill_Address" => $vnp_Bill_Address,
                        // "vnp_Bill_City" => $vnp_Bill_City,
                        // "vnp_Bill_Country" => $vnp_Bill_Country,
                        "vnp_Inv_Phone" => $tell,
                        "vnp_Inv_Email" => $email
                        // "vnp_Inv_Customer" => $vnp_Inv_Customer,
                        // "vnp_Inv_Address" => $vnp_Inv_Address,
                        // "vnp_Inv_Company" => $vnp_Inv_Company,
                        // "vnp_Inv_Taxcode" => $vnp_Inv_Taxcode,
                        // "vnp_Inv_Type" => $vnp_Inv_Type
                    );


                    if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                        $inputData['vnp_BankCode'] = $vnp_BankCode;
                    }

                    // if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
                    //     $inputData['vnp_Bill_State'] = $vnp_Bill_State;
                    // }
                    ksort($inputData);
                    $query = "";
                    $i = 0;
                    $hashdata = "";
                    foreach ($inputData as $key => $value) {
                        if ($i == 1) {
                            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                        } else {
                            $hashdata .= urlencode($key) . "=" . urlencode($value);
                            $i = 1;
                        }
                        $query .= urlencode($key) . "=" . urlencode($value) . '&';
                    }

                    $vnp_Url = $vnp_Url . "?" . $query;
                    if (isset($vnp_HashSecret)) {
                        $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
                        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
                    }
                    $returnData = array(
                        'code' => '00', 'message' => 'success', 'data' => $vnp_Url
                    );
                    foreach ($_SESSION['mycart'] as $cart) {
                        insert_cart($_SESSION['email']['id'], $cart['id'], $cart['img'], $cart['name'], $cart['price'], $cart['qty'], $idbill);
                        $sp .= $cart['name'] . " x " . $cart['qty'] . "\n";
                    }
                    $thongtin = "ID-ĐH: " . $idbill . "\nTên: " . $lastname . "\nEmail: " . $email . "\nĐịa chỉ: " . $address . "\nPhone: " . $tell   . "\nSản phẩm\n" . $sp . "Tổng: " . number_format($tongdonhang, 0, ',', '.') . " VNĐ\n" . "Note: " . $note;
                    // $cart = $cart["name'];                  
                    // $thongtin = 'Tên ' .  urlencode($lastname) . 'Email' . urldecode($email) . 'Địa chỉ' . urldecode($address) . 'Phone' . urldecode($tell)   . 'Tổng' . urldecode($tongdonhang);  
                    // $cart = $cart['name'];                   
                    $_SESSION['order'] = 'order';
                    $_SESSION['mycart'] = [];
                    $_SESSION['tongtien'] = 0;
                    $thongtin = urlencode($thongtin);
                    file_get_contents("https://api.telegram.org/bot2123578038:AAGBFpDM9rG6uYDSBlkwq9qoGH3Yx5NOkVs/sendMessage?parse_mode=html&chat_id=-716594182&text=" . $thongtin);
                    // header('Location: index.php?act=view_order');
                    header('Location: ' . $vnp_Url);
                    die();
                }
                $sp = '';
                foreach ($_SESSION['mycart'] as $cart) {
                    insert_cart($_SESSION['email']['id'], $cart['id'], $cart['img'], $cart['name'], $cart['price'], $cart['qty'], $idbill);
                    $sp .= $cart['name'] . " x " . $cart['qty'] . "\n";
                }
                $thongtin = "ID-ĐH: " . $idbill . "\nTên: " . $lastname . "\nEmail: " . $email . "\nĐịa chỉ: " . $address . "\nPhone: " . $tell   . "\nSản phẩm\n" . $sp . "Tổng: " . number_format($tongdonhang, 0, ',', '.') . " VNĐ\n" . "Note: " . $note;
                // $cart = $cart["name'];                  
                // $thongtin = 'Tên ' .  urlencode($lastname) . 'Email' . urldecode($email) . 'Địa chỉ' . urldecode($address) . 'Phone' . urldecode($tell)   . 'Tổng' . urldecode($tongdonhang);  
                // $cart = $cart['name'];                   
                $_SESSION['order'] = 'order';
                $_SESSION['mycart'] = [];
                $_SESSION['tongtien'] = 0;
                $thongtin = urlencode($thongtin);
                file_get_contents("https://api.telegram.org/bot2123578038:AAGBFpDM9rG6uYDSBlkwq9qoGH3Yx5NOkVs/sendMessage?parse_mode=html&chat_id=-716594182&text=" . $thongtin);
                header('Location: index.php?act=view_order');
            }
            // include "view/sanpham/checkout.php";
            break;
        case 'updatesstbill':
            if (isset($_GET['vnp_TxnRef'])) {
                $update = update('Đã thanh toán', $_GET['vnp_TxnRef']);
                header('Location: index.php?act=view_order');
            }

            break;

        default:
            include "view/home.php";
            break;
    }
} else {

    include "view/home.php";
}
include "view/footer.php";