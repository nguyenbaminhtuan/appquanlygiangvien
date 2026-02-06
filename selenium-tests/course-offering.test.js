import { Builder, By, until, Key } from 'selenium-webdriver';
import chrome from 'selenium-webdriver/chrome.js';
import assert from 'assert';
import fs from 'fs';

// Hàm helper để đăng nhập
async function loginAsAdmin(driver) {
    await driver.get('http://127.0.0.1:8000/login');
    await driver.wait(until.elementLocated(By.name('email')), 10000);
    await driver.findElement(By.name('email')).sendKeys('admin@example.com');
    await driver.findElement(By.name('password')).sendKeys('password', Key.RETURN);
    await driver.wait(until.urlContains('/dashboard'), 10000);
}

describe('Mở Lớp học phần hàng loạt', function() {
    this.timeout(40000);
    let driver;

    before(async () => {
        const service = new chrome.ServiceBuilder('D:\\chromedriver-win64\\chromedriver.exe'); // Sửa lại đường dẫn nếu cần
        driver = await new Builder().forBrowser('chrome').setChromeService(service).build();
        await loginAsAdmin(driver);
    });

    after(async () => {
        await driver.quit();
    });

    it('TC_OFFERING_01: Nên mở thành công nhiều lớp học phần cho một học phần', async () => {
        const timestamp = Date.now().toString().slice(-4);
        try {
            // 1. Điều hướng đến trang mở lớp hàng loạt
            await driver.get('http://127.0.0.1:8000/admin/course-offerings/open-batch');
            await driver.wait(until.elementLocated(By.id('semester_id')), 5000);

            // 2. Điền form
            // Chọn Kì học (giả sử Kì học có id=1 đã tồn tại)
            let semesterSelect = await driver.findElement(By.id('semester_id'));
            await semesterSelect.findElement(By.css("option[value='1']")).click(); // <<--- SỬA ID KÌ HỌC NẾU CẦN

            // Chọn Học phần (giả sử Học phần có id=1 đã tồn tại)
            let subjectSelect = await driver.findElement(By.id('subject_id'));
            await subjectSelect.findElement(By.css("option[value='1']")).click(); // <<--- SỬA ID HỌC PHẦN NẾU CẦN
            
            // Lấy mã học phần để kiểm tra sau này
            const selectedSubjectOption = await subjectSelect.findElement(By.css("option:checked"));
            let subjectText = await selectedSubjectOption.getText(); // Ví dụ: "Nhập môn Lập trình (IT101) - 30 tiết chuẩn"
            const subjectCodeMatch = subjectText.match(/\(([^)]+)\)/); // Lấy phần trong ngoặc đơn
            const subjectCode = subjectCodeMatch ? subjectCodeMatch[1] : `TEST${timestamp}`;

            // Điền các thông tin khác
            await driver.findElement(By.id('number_of_classes')).clear(); // Xóa giá trị mặc định
            await driver.findElement(By.id('number_of_classes')).sendKeys('3');
            
            await driver.findElement(By.id('max_students_per_class')).clear(); // Xóa giá trị mặc định
            await driver.findElement(By.id('max_students_per_class')).sendKeys('60');

            // 3. Nhấn nút "Mở Lớp"
            await driver.findElement(By.css('button[type=submit]')).click();

            // 4. Kiểm tra kết quả
            await driver.wait(until.elementLocated(By.css('div[role=alert]')), 10000);
            const successMessage = await driver.findElement(By.css('div[role=alert]')).getText();
            
            // Kiểm tra thông báo thành công
            assert.ok(successMessage.includes('Đã mở thành công 3 lớp'), 'Thông báo thành công không chính xác.');
            // Kiểm tra các mã lớp được tạo ra
            assert.ok(successMessage.includes(`${subjectCode}.N01`), 'Không thấy mã lớp .N01 trong thông báo.');
            assert.ok(successMessage.includes(`${subjectCode}.N02`), 'Không thấy mã lớp .N02 trong thông báo.');
            assert.ok(successMessage.includes(`${subjectCode}.N03`), 'Không thấy mã lớp .N03 trong thông báo.');

        } catch (error) {
            await driver.takeScreenshot().then((image) => {
                fs.writeFileSync(`selenium-tests/error_create_offering_${timestamp}.png`, image, 'base64');
            });
            throw error;
        }
    });

    it('TC_OFFERING_02: Nên hiển thị lỗi validation khi không chọn Kì học', async () => {
         try {
            // 1. Điều hướng đến trang mở lớp hàng loạt
            await driver.get('http://127.0.0.1:8000/admin/course-offerings/open-batch');
            await driver.wait(until.elementLocated(By.id('subject_id')), 5000);

            // 2. Chỉ chọn Học phần, không chọn Kì học
            await driver.findElement(By.id('subject_id')).findElement(By.css("option[value='1']")).click();
            await driver.findElement(By.id('number_of_classes')).sendKeys('2');

            // 3. Nhấn nút "Mở Lớp"
            await driver.findElement(By.css('button[type=submit]')).click();

            // 4. Kiểm tra kết quả
            await driver.wait(until.elementLocated(By.css('div[role=alert]')), 5000);
            const errorDiv = await driver.findElement(By.css('div[role=alert]'));
            const errorText = await errorDiv.getText();
            assert.ok(errorText.includes('semester id field is required'), 'Không hiển thị lỗi validation cho Kì học.');

         } catch (error) {
            await driver.takeScreenshot().then((image) => {
                fs.writeFileSync(`selenium-tests/error_offering_validation_${timestamp}.png`, image, 'base64');
            });
            throw error;
         }
    });
});