var currentUser;
var tongTienTatCaDonHang = 0; // lưu tổng tiền từ tất cả các đơn hàng đã mua
var tongSanPhamTatCaDonHang = 0;
let DSDH = []
let dataUser = {}

window.onload = function () {
    khoiTao();
    addEventChangeTab();

    // thêm tags (từ khóa) vào khung tìm kiếm
    var tags = ["Samsung", "iPhone", "Huawei", "Oppo", "Mobi"];
    for (var t of tags) addTags(t, "index.php?search=" + t);

    getCurrentUser((data) => {
        setAvatar(data)
        dataUser = data
        console.log(data);
        const date = getDate()
        const month = getMonth()
        const year = getYear()
        document.getElementById('form').innerHTML = `
        <div class="left-form">
            <div class="field">
                <label>Tên đăng nhập</label>
                ${data.TaiKhoan}
            </div>
            <div class="field">
                <label>Tên</label>
                <input type="text" value="${data.Ten}"></input>
            </div>
            <div class="field">
                <label>Email</label>
                ${data.Email}
            </div>
            <div class="field">
                <label>Giới tính</label>
                <label class="radio-label">
                    <input type="radio" name="radio">
                    <span class="checkmark"></span>
                    Nam
                </label>
                <label class="radio-label">
                    <input type="radio" name="radio">
                    <span class="checkmark"></span>
                    Nữ
                </label>
                <label class="radio-label">
                    <input type="radio" name="radio">
                    <span class="checkmark"></span>
                    Khác
                </label>
            </div>
            <div class="field">
                <label>Ngày sinh</label>
                <select name="birthday" id="birthday" class="my-select">
                    ${date}
                </select>
                <select name="birthday" id="month" class="my-select">
                    ${month}
                </select>
                <select name="birthday" id="year" class="my-select">
                    ${year}
                </select>
            </div>
        </div>
        <div class="right-form">
            <div class="thumb">
                <img id="my-avatar" src="img/avata.jpg" class="image">
            </div>
            <button type="button" class="imgSelect" onClick="selectImage()">Chọn ảnh</button>
            <input id="upfile" type="file" accept="image/png, image/jpeg" hidden onchange="ValidateAvata(this);" />
            <p class="info">Dung lượng tối đa 1MB</p>
            <p class="info">Định dạng JPEG, PNG</p>
        </div>
        <div class="form-submit">
            <button type="button" class="submit" onClick="onSubmit()">Lưu</button>
        </div>
        `
        if(data) {
            $.ajax({
                type: "POST",
                url: "php/getdonhang.php",
                dataType: "json",
                data: {
                    request: "getall",
                },
                success: function(data) {
                    DSDH = data
                    locdonhang(-1)
                },
                error: function(e) {
                    console.log(e.responseText);
                }
            })
        } else {
            var warning = `<h2 style="color: red; font-weight:bold; text-align:center; font-size: 2em; padding: 50px;">
                            Bạn chưa đăng nhập !!
                        </h2>`;
            document.getElementsByClassName('infoUser')[0].innerHTML = warning;
        }

    }, (e)=> {
        console.log(e.responseText);
    })
}


function xemChiTiet(mahd) {
    $.ajax({
        type: "GET",
        url: "php/tablechitietdonhang.php",
        data: {
            mahd: mahd
        },
        success: function(data) {
            $("#chitietdonhang").html(data);
        },
        error: function(e) {
            console.log(e.responseText);
        }
    });
}

// Phần Thông tin người dùng
function addInfoUser(user) {
    if (!user) return;
    document.getElementsByClassName('infoUser')[0].innerHTML = `
    <hr>
    <table>
        <tr>
            <th colspan="3">THÔNG TIN KHÁCH HÀNG</th>
        </tr>
        <tr>
            <td>Tài khoản: </td>
            <td> <input type="text" value=" + user.username + " readonly> </td>
            <td> <i class="fa fa-pencil" onclick="changeInfo(this, 'username')"></i> </td>
        </tr>
        <tr>
            <td>Mật khẩu: </td>
            <td style="text-align: center;"> 
                <i class="fa fa-pencil" id="butDoiMatKhau" onclick="openChangePass()"> Đổi mật khẩu</i> 
            </td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3" id="khungDoiMatKhau">
                <table>
                    <tr>
                        <td> <div>Mật khẩu cũ:</div> </td>
                        <td> <div><input type="password"></div> </td>
                    </tr>
                    <tr>
                        <td> <div>Mật khẩu mới:</div> </td>
                        <td> <div><input type="password"></div> </td>
                    </tr>
                    <tr>
                        <td> <div>Xác nhận mật khẩu:</div> </td>
                        <td> <div><input type="password"></div> </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td> 
                            <div><button onclick="changePass()">Đồng ý</button></div> 
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>Họ: </td>
            <td> <input type="text" value=" + user.ho + " readonly> </td>
            <td> <i class="fa fa-pencil" onclick="changeInfo(this, 'ho')"></i> </td>
        </tr>
        <tr>
            <td>Tên: </td>
            <td> <input type="text" value=" + user.ten + " readonly> </td>
            <td> <i class="fa fa-pencil" onclick="changeInfo(this, 'ten')"></i> </td>
        </tr>
        <tr>
            <td>Email: </td>
            <td> <input type="text" value=" + user.email + " readonly> </td>
            <td> <i class="fa fa-pencil" onclick="changeInfo(this, 'email')"></i> </td>
        </tr>
        <tr>
            <td colspan="3" style="padding:5px; border-top: 2px solid #ccc;"></td>
        </tr>
        <tr>
            <td>Tổng tiền đã mua: </td>
            <td> <input type="text" value="` + numToString(tongTienTatCaDonHang) + `₫" readonly> </td>
            <td></td>
        </tr>
        <tr>
            <td>Số lượng sản phẩm đã mua: </td>
            <td> <input type="text" value=" + tongSanPhamTatCaDonHang + " readonly> </td>
            <td></td>
        </tr>
    </table>`;
}

function openChangePass() {
    var khungChangePass = document.getElementById('khungDoiMatKhau');
    var actived = khungChangePass.classList.contains('active');
    if (actived) khungChangePass.classList.remove('active');
    else khungChangePass.classList.add('active');
}

function changePass() {
    var khungChangePass = document.getElementById('khungDoiMatKhau');
    var inps = khungChangePass.getElementsByTagName('input');
    if (inps[0].value != currentUser.pass) {
        Swal.fire({
            type: 'error',
            title: 'Sai mật khẩu'
        }).then((result) => {
            inps[0].focus();
        });
        return;
    }
    if (inps[1] == '') {
        Swal.fire({
            type: 'error',
            title: 'Chưa nhập mật khẩu mới !'
        })
        inps[1].focus();
    }
    if (inps[1].value != inps[2].value) {
        Swal.fire({
            type: 'error',
            title: 'Mật khẩu không khớp'
        }).then((result) => {
            inps[2].focus();
        });
        return;
    }

    var temp = copyObject(currentUser);
    currentUser.pass = inps[1].value;

    // cập nhật danh sách sản phẩm trong localstorage
    setCurrentUser(currentUser);
    updateListUser(temp, currentUser);

    // Cập nhật trên header
    capNhat_ThongTin_CurrentUser();

    // thông báo
    Swal.fire({
        type: 'success',
        title: 'Xong',
        text: 'Thay đổi mật khẩu thành công.'
    }).then((result) => {
        inps[0].value = inps[1].value = inps[2].value = "";
        openChangePass();    
    });
    // addAlertBox('Thay đổi mật khẩu thành công.', '#5f5', '#000', 4000);
}

function changeInfo(iTag, info) {
    var inp = iTag.parentElement.previousElementSibling.getElementsByTagName('input')[0];

    // Đang hiện
    if (!inp.readOnly && inp.value != '') {

        if (info == 'username') {
            var users = getListUser();
            for (var u of users) {
                if (u.username == inp.value && u.username != currentUser.username) {
                    alert('Tên đã có người sử dụng !!');
                    inp.value = currentUser.username;
                    return;
                }
            }
            // Đổi tên trong list đơn hàng
            if (!currentUser.donhang.length) {
                document.getElementsByClassName('listDonHang')[0].innerHTML = `
                    <h3 style="width=100%; padding: 50px; color: green; font-size: 2em; text-align: center"> 
                        Xin chào  + inp.value + . Bạn chưa có đơn hàng nào.
                    </h3>`;
            }


        } else if (info == 'email') {
            var users = getListUser();
            for (var u of users) {
                if (u.email == inp.value && u.username != currentUser.username) {
                    alert('Email đã có người sử dụng !!');
                    inp.value = currentUser.email;
                    return;
                }
            }
        }

        var temp = copyObject(currentUser);
        currentUser[info] = inp.value;

        // cập nhật danh sách sản phẩm trong localstorage
        setCurrentUser(currentUser);
        updateListUser(temp, currentUser);

        // Cập nhật trên header
        capNhat_ThongTin_CurrentUser();

        iTag.innerHTML = '';

    } else {
        iTag.innerHTML = 'Đồng ý';
        inp.focus();
        var v = inp.value;
        inp.value = '';
        inp.value = v;
    }

    inp.readOnly = !inp.readOnly;
}


// Phần thông tin đơn hàng
function addTatCaDonHang(user) {
    if (!user) {
        document.getElementsByClassName('listDonHang')[0].innerHTML = `
            <h3 style="width=100%; padding: 50px; color: red; font-size: 2em; text-align: center"> 
                Bạn chưa đăng nhập !!
            </h3>`;
        return;
    }
    if (!user.donhang.length) {
        document.getElementsByClassName('listDonHang')[0].innerHTML = `
            <h3 style="width=100%; padding: 50px; color: green; font-size: 2em; text-align: center"> 
                Xin chào  + currentUser.username + . Bạn chưa có đơn hàng nào.
            </h3>`;
        return;
    }
    for (var dh of user.donhang) {
        addDonHang(dh);
    }
}

function addDonHang(dh) {
    var div = document.getElementsByClassName('listDonHang')[0];

    var s = `
            <table class="listSanPham">
                <tr> 
                    <th colspan="6">
                        <h3 style="text-align:center;"> Đơn hàng ngày: ` + new Date(dh.ngaymua).toLocaleString() + `</h3> 
                    </th>
                </tr>
                <tr>
                    <th>STT</th>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Thời gian thêm vào giỏ</th> 
                </tr>`;

    var totalPrice = 0;
    for (var i = 0; i < dh.sp.length; i++) {
        var masp = dh.sp[i].ma;
        var soluongSp = dh.sp[i].soluong;
        var p = timKiemTheoMa(list_products, masp);
        var price = (p.promo.name == 'giareonline' ? p.promo.value : p.price);
        var thoigian = new Date(dh.sp[i].date).toLocaleString();
        var thanhtien = stringToNum(price) * soluongSp;

        s += `
                <tr>
                    <td>` + (i + 1) + `</td>
                    <td class="noPadding imgHide">
                        <a target="_blank" href="chitietsanpham.php?` + p.name.split(' ').join('-') + `" title="Xem chi tiết">
                             + p.name + 
                            <img src=" + p.img + ">
                        </a>
                    </td>
                    <td class="alignRight">` + price + ` ₫</td>
                    <td class="soluong" >
                          + soluongSp + 
                    </td>
                    <td class="alignRight">` + numToString(thanhtien) + ` ₫</td>
                    <td style="text-align: center" >` + thoigian + `</td>
                </tr>
            `;
        totalPrice += thanhtien;
        tongSanPhamTatCaDonHang += soluongSp;
    }
    tongTienTatCaDonHang += totalPrice;

    s += `
                <tr style="font-weight:bold; text-align:center; height: 4em;">
                    <td colspan="4">TỔNG TIỀN: </td>
                    <td class="alignRight">` + numToString(totalPrice) + ` ₫</td>
                    <td >  + dh.tinhTrang +  </td>
                </tr>
            </table>
            <hr>
        `;
    div.innerHTML += s;
}

function locdonhang(status) {
    let currentData = DSDH
    if (status > -1) {
        currentData = currentData.filter(data => data.TrangThai == status)
    }
    let donhang = '';
    if (!currentData.length) {
        $(".my-bill").html('');
        return
    }
    currentData.forEach((item, index) => {
        let chitietdonhang = '';
        let listMaSP = [];
        let listTenSP = [];
        item.CTHD.forEach((ctdh) => {
            listMaSP = [...listMaSP, ctdh.MaSP]
            listTenSP = [...listTenSP, ctdh.TenSP]
            chitietdonhang +=`<img src="${ctdh.HinhAnh}" class="image">
            <div class="info">
                <p>Tên sản phẩm: ${ctdh.TenSP}</p>
                <p>Số lượng: ${ctdh.SoLuong}</p>
                <p class="price">${parseInt(ctdh.DonGia).toLocaleString()} VND </p>
            </div>`
        })
        const classFirst = index === 0 ? '-first' : '';
		const trangThaiDonHang = formatStatuses(item.TrangThai);
        donhang +=`<div class="item ${classFirst}">
					<div class="head">
						<span class="id">Mã đơn hàng: ${item.MaHD}</span>
						<span class="time">Thời gian: ${item.NgayLap}</span>
						<span class="status">${trangThaiDonHang}</span>
					</div>
					<div class="content">
                    ${chitietdonhang}
					</div>
				</div>
				<div class="contact">
				<div class="total">Tổng tiền: ${parseInt(item.TongTien).toLocaleString()} VND</div>
				<div class="action">
					<button class="buy" onclick="return themVaoGioHang('${listMaSP}', '${listTenSP}')">Mua lại</button>
					<button>Liên hệ người bán</button>
				</div>
			</div>
		</div>`
    });

    $(".my-bill").html(donhang);
}

function addEventChangeTab() {
    var status = {
        tatca: -1,
        choxacnhan: 0,
        danggiao: 1,
        dagiao: 2,
        dahuy: 3
    }
    var tab = document.getElementsByClassName('menutab')[0];
    var list_span = tab.getElementsByTagName('span');
    for (var span of list_span) {
        if (!span.onclick) {
            span.addEventListener('click', function() {
                turnOff_Active();
                this.classList.add('active');
                locdonhang(status[this.id])
            })
        }
    }

    var hoso = document.getElementById('hoso');
    var donmua = document.getElementById('donmua');

    document.getElementById('invoice').style.display= 'none'
    hoso.addEventListener('click', function() {
        document.getElementById('profile').style.display= 'block'
        document.getElementById('invoice').style.display= 'none'
    })

    donmua.addEventListener('click', function() {
        document.getElementById('profile').style.display= 'none'
        document.getElementById('invoice').style.display= 'block'
    })
}

function turnOff_Active() {
    var tab = document.getElementsByClassName('menutab')[0];
    var list_span = tab.getElementsByTagName('span');
    for (var spab of list_span) {
        spab.classList.remove('active');
    }
}

function formatStatuses($status) {
    switch ($status) {
        case '0':
            return 'CHỜ XÁC NHẬN';
        case '1':
            return 'ĐANG GIAO'; 
        case '2':
            return 'ĐÃ GIAO';
        case '3':
            return 'ĐÃ HUỶ';
    }
    return 'Status error';
}

function setAvatar(data) {
    const avata = data.avata || 'img/avata.jpg'
    document.getElementById('avatar').innerHTML = `
    <img src="${avata}"></img>
    <span>${data.TaiKhoan}</span>
    `
}

function getDate() {
    let date= ''
    for (let index = 1; index <= 31; index++) {
        date += `<option value="${index}">${index}</option>`
        
    }
    return date
}

function getMonth() {
    let month= ''
    for (let index = 1; index <= 12; index++) {
        month += `<option value="${index}">Tháng ${index}</option>`
        
    }
    return month
}

function getYear() {
    let year = ''
    const currentYear = new Date().getFullYear()
    for (let index = 1900; index <= currentYear; index++) {
        year += `<option value="${index}">${index}</option>`
        
    }
    return year
}

function selectImage() {
    document.getElementById("upfile").click();
}

function ValidateAvata(oInput) {
    const fileSize = oInput.files[0].size / 1024 / 1024;
    const preview = document.getElementById('avatar');


    if (fileSize > 1) {
        alert("Ảnh không đúng dung lượng!");
        oInput.value = "";
        preview.src = "img/avata.jpg";
        return false;
    }
    const fileType = [".jpg", ".jpeg", ".png"];
    if (oInput.type == "file") {
        const fileName = oInput.value;
        let isValid = false;
         if (fileName.length > 0) {
            for (let j = 0; j < fileType.length; j++) {
                const sCurExtension = fileType[j];
                if (fileName.substr(fileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    isValid = true;
                    break;
                }
            }
        }

        if (!isValid) {
            alert("File không đúng định dạng!");
            oInput.value = "";
            preview.src = "img/avata.jpg";
            return false;
        } else {
            const preview = document.getElementById('my-avatar');
            const file    = document.getElementById('upfile').files[0];
            const reader  = new FileReader();
            reader.onloadend = function () {
                preview.src = reader.result;
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
            }
        }
    }
    console.log(document.getElementById('upfile').files[0]);
    return true;
}

function onSubmit () {
    console.log(dataUser);
}
