<?php
include_once 'model/connect.php';
include_once 'model/cart.php';
include_once 'model/user.php';
include_once "model/view.php";
if ($_GET['act']) {
    switch ($_GET['act']) {
        case 'logout':
            // đăng xuất
            if (isset($_SESSION['user'])) {
                unset($_SESSION['user']);
            }
            header("Location: ?mod=page&act=home");

            include_once 'view/template_head.php';
            include_once 'view/template_header.php';
            include_once 'view/template_banner.php';
            include_once 'view/template_near_footer.php';
            include_once 'view/template_footer.php';
            break;
        case 'login':
            // if (isset($_POST['btn_login'])) {
            //     $password = $_POST['password'];
            //     $email = $_POST['email'];
            //     // kiểm tra username và password
            //     if (check_login($email, $password)) {
            //         $userinfo = check_login($email, $password);
            //         $_SESSION['user'] =  $userinfo;
            //         header("Location: ?mod=page&act=home");
            //     } else {
            //         echo "<script>alert('Sai tên đăng nhập hoặc mật khẩu');</script>";
            //     }
            // }
            if (isset($_POST['btn_login'])) {
                $kq = check_login($_POST['email'], $_POST['password']);
                if ($kq) {
                    $_SESSION['user'] = $kq;
                    if ($kq['role'] == '1') {
                        header("Location: ?mod=product&act=admin_product");
                    } else {
                        header("Location: ?mod=page&act=home");
                    }
                } else {
                    echo "<script>alert('Sai tên đăng nhập hoặc mật khẩu');</script>";
                }
            }


            $tendm = "Đăng nhập tài khoản";
            $pathpage = "Trang chủ | " . $tendm;
            $pathpage_a = "<div class='path'><a href='index.php'> Trang chủ </a> > <span>$tendm</span> </div>";

            include_once 'view/template_head.php';
            include_once 'view/template_header.php';
            include_once 'view/template_banner.php';
            include_once 'view/page_login.php';
            // include_once 'view/template_near_footer.php';
            // include_once 'view/template_footer.php';
            break;
        case 'signup':
            // đăng kí
            if (isset($_POST['btn_signup'])) {
                $username = $_POST['username'];
                $fullname = $_POST['fullname'];
                $password = $_POST['password'];
                $email = $_POST['email'];

                // ghi nhận username
                $_SESSION['username'] = $username;
                insert_user($username, $fullname, $email, $password);
                // auto login
                header("Location: ?mod=user&act=login");
            }

            $tendm = "Đăng ký tài khoản";
            $pathpage = "Trang chủ | " . $tendm;
            $pathpage_a = "<div class='path'><a href='index.php'> Trang chủ </a> > <span>$tendm</span> </div>";

            include_once 'view/template_head.php';
            include_once 'view/template_header.php';
            include_once 'view/template_banner.php';
            include_once 'view/page_signup.php';
            include_once 'view/template_near_footer.php';
            include_once 'view/template_footer.php';
            break;
        case 'userupdate':
            // đăng kí
            if (isset($_POST['btn_update'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $email = $_POST['email'];
                $fullname = $_POST['fullname'];
                $address = $_POST['address'];
                $phone = $_POST['phone'];

                update_user($email, $fullname, $address, $phone, $password, $username);
                $_SESSION['user']['username'] = $username;
                $_SESSION['user']['fullname'] = $fullname;
                $_SESSION['user']['email'] = $email;
                $_SESSION['user']['phone'] = $phone;
                $_SESSION['user']['address'] = $address;

                // header("Location: ?mod=user&act=userupdate");
                header("Location: ?mod=page&act=home");
            }

            $tendm = "Cập nhật tài khoản";
            $pathpage = "Trang chủ | " . $tendm;
            $pathpage_a = "<div class='path'><a href='index.php'> Trang chủ </a> > <span>$tendm</span> </div>";

            include_once 'view/template_head.php';
            include_once 'view/template_header.php';
            include_once 'view/template_banner.php';
            include_once 'view/page_userupdate.php';
            include_once 'view/template_near_footer.php';
            include_once 'view/template_footer.php';
            break;
        case 'client':
            $tendm = "Trang khách hàng";
            $pathpage = "Trang chủ | " . $tendm;
            $pathpage_a = "<div class='path'><a href='index.php'> Trang chủ </a> > <span>$tendm</span> </div>";

            include_once 'view/template_head.php';
            include_once 'view/template_header.php';
            include_once 'view/template_banner.php';
            include_once 'view/page_client.php';
            include_once 'view/template_near_footer.php';
            include_once 'view/template_footer.php';
            break;
        case 'admin_user';
            $users = getAllUserNoLimit();
            include_once "view/admin_user.php";
            break;
        case 'add_user':
            if (!(isset($_SESSION['user']) && $_SESSION['user']['role'] == '1')) {
                header('Location: ?mod=page&act=home');
                exit();
            }
            if (isset($_POST['submit'])) {
                $kq = add_user(
                    $_POST['up_name'],
                    $_POST['up_email'],
                    $_POST['up_password'],
                    $_POST['up_phone'],
                    $_POST['up_address'],
                    $_POST['up_role']
                );
                if ($kq) {
                    header('Location: ?mod=user&act=admin_user');
                } else {
                    echo "<script>alert('Lỗi khi thêm người dùng vào cơ sở dữ liệu');</script>";
                }
            }
            include_once "view/user_add.php";
            break;
        case 'edit_user':
            if (!(isset($_SESSION['user']) && $_SESSION['user']['role'] == '1')) {
                header('Location: ?mod=page&act=home');
                exit();
            }
            if (isset($_POST['submit'])) {
                $user_id = $_POST['user_id'];
                $kq = update_ad_user(
                    $user_id,
                    $_POST['up_name'],
                    $_POST['up_email'],
                    $_POST['up_password'],
                    $_POST['up_phone'],
                    $_POST['up_address'],
                    $_POST['up_role']
                );
                if ($kq) {
                    header("Location: admin.php?mod=user&act=admin_user");
                    exit();
                } else {
                    echo "<script>alert('Lỗi khi cập nhật thông tin người dùng');</script>";
                }
            }
            $user_id = $_GET['id'];
            $user = get_user_by_id($user_id);
            include_once "view/user_edit.php";
            break;
        case 'delete_user';
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $kq = delete_user($_GET['id']);
                if ($kq) {
                    header("Location: admin.php?mod=user&act=admin_user");
                    exit();
                } else {
                    echo "<script>
                        alert('Có lỗi xảy ra khi xóa sản phẩm');
                      </script>";
                }
            } else {
                echo "<script>alert('ID sản phẩm không hợp lệ');</script>";
            }
            break;
        default:
            # 404 - trang web không tồn tại!
            break;
    }
}
