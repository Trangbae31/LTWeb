# TEST CASE – BÀI TẬP BUỔI 5: PDO VÀ CRUD

## 1. Mục đích kiểm thử

Kiểm tra các chức năng thêm, sửa, xóa, tìm kiếm đặt phòng và khả năng xử lý dữ liệu không hợp lệ của chương trình.

## 2. Danh sách test case

| Mã TC | Chức năng | Dữ liệu đầu vào | Kết quả mong đợi | Kết quả thực tế | Đánh giá |
|---|---|---|---|---|---|
| TC01 | Thêm đặt phòng hợp lệ | Tiêu đề: Test CRUD Buoi 5; phòng A101; thời gian 09:00–10:00 ngày 29/10/2026 | Thêm đặt phòng thành công, dữ liệu được lưu vào CSDL. | Hệ thống thêm đặt phòng thành công. Bản ghi xuất hiện trong danh sách và được lưu vào CSDL. | Đạt |
| TC02 | Thêm đặt phòng thiếu tiêu đề | Để trống trường tiêu đề, các trường khác hợp lệ. | Hiển thị thông báo lỗi, không thêm dữ liệu vào CSDL. | Hệ thống thông báo yêu cầu nhập tiêu đề. Bản ghi không được thêm vào CSDL. | Đạt |
| TC03 | Kiểm tra thời gian không hợp lệ | Thời gian bắt đầu 10:00, thời gian kết thúc 09:00 cùng ngày. | Từ chối dữ liệu có thời gian kết thúc trước thời gian bắt đầu. | Hệ thống từ chối dữ liệu không hợp lệ và không tạo bản ghi mới. | Đạt |
| TC04 | Tìm kiếm đặt phòng | Nhập từ khóa `Thuc hanh PHP`. | Hiển thị các đặt phòng phù hợp với từ khóa. | Danh sách được lọc theo từ khóa tìm kiếm; bản ghi phù hợp được hiển thị. | Đạt |
| TC05 | Sửa và xóa đặt phòng | Sửa tiêu đề thành `Test CRUD Buoi 5 - Da sua`, sau đó xóa bản ghi kiểm thử. | Dữ liệu được cập nhật và bản ghi được xóa khỏi danh sách. | Tiêu đề được cập nhật thành công. Sau khi xóa, bản ghi không còn xuất hiện trong danh sách. | Đạt |

## 3. Đánh giá

Qua các trường hợp kiểm thử, chương trình đáp ứng các chức năng thêm, sửa, xóa và tìm kiếm đặt phòng. Hệ thống cũng xử lý các trường hợp dữ liệu đầu vào không hợp lệ, góp phần đảm bảo tính chính xác của dữ liệu trong cơ sở dữ liệu.