<?php
// ============================================================
//  View\Admin\Route — Bảng định tuyến Admin (tài liệu)
// ============================================================
/*
 | URL                            | Controller | Action           | Mô tả
 |-------------------------------|------------|------------------|------------------------------
 | GET  /admin                   | Admin      | index            | Dashboard tổng quan
 | GET  /admin/book              | Admin      | book             | Danh sách sách
 | GET  /admin/addbook           | Admin      | addbook          | Form thêm sách
 | POST /admin/storebook         | Admin      | storebook        | Lưu sách mới
 | GET  /admin/editbook/{id}     | Admin      | editbook($id)    | Form sửa sách
 | POST /admin/updatebook/{id}   | Admin      | updatebook($id)  | Lưu sửa sách
 | GET  /admin/deletebook/{id}   | Admin      | deletebook($id)  | Xóa sách
 | GET  /admin/danhmuc           | Admin      | danhmuc          | Danh sách danh mục
 | POST /admin/storedanhmuc      | Admin      | storedanhmuc     | Lưu danh mục mới
 | POST /admin/updatedanhmuc/{id}| Admin      | updatedanhmuc    | Sửa danh mục
 | GET  /admin/deletedanhmuc/{id}| Admin      | deletedanhmuc    | Xóa danh mục
 | GET  /admin/theloai           | Admin      | theloai          | Danh sách thể loại
 | POST /admin/storetheloai      | Admin      | storetheloai     | Lưu thể loại mới
 | POST /admin/updatetheloai/{id}| Admin      | updatetheloai    | Sửa thể loại
 | GET  /admin/deletetheloai/{id}| Admin      | deletetheloai    | Xóa thể loại
 | GET  /admin/tacgia            | Admin      | tacgia           | Danh sách tác giả
 | POST /admin/storetacgia       | Admin      | storetacgia      | Lưu tác giả mới
 | POST /admin/updatetacgia/{id} | Admin      | updatetacgia     | Sửa tác giả
 | GET  /admin/deletetacgia/{id} | Admin      | deletetacgia     | Xóa tác giả
 | GET  /admin/nxb               | Admin      | nxb              | Danh sách nhà xuất bản
 | POST /admin/storenxb          | Admin      | storenxb         | Lưu NXB mới
 | POST /admin/updatenxb/{id}    | Admin      | updatenxb        | Sửa NXB
 | GET  /admin/deletenxb/{id}    | Admin      | deletenxb        | Xóa NXB
 | GET  /admin/donhang           | Admin      | donhang          | Danh sách đơn hàng
 | POST /admin/updatedonhang     | Admin      | updatedonhang    | AJAX cập nhật TT đơn
 | POST /admin/loadchitiet       | Admin      | loadchitiet      | AJAX load SP trong đơn
 | GET  /admin/nguoidung         | Admin      | nguoidung        | Danh sách người dùng
 | GET  /admin/capquyen/{id}     | Admin      | capquyen($id)    | AJAX cấp quyền admin
 | GET  /admin/haquyen/{id}      | Admin      | haquyen($id)     | AJAX hạ quyền user
 | GET  /admin/danhgia           | Admin      | danhgia          | Danh sách đánh giá
 | GET  /admin/deletedanhgia/{id}| Admin      | deletedanhgia    | Xóa đánh giá
*/
