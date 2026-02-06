import { Builder, By, until, Key } from 'selenium-webdriver';
import chrome from 'selenium-webdriver/chrome.js';
import assert from 'assert';

// Hàm helper để đăng nhập (có thể tách ra file riêng để tái sử dụng)
async function loginAsAdmin(driver) {
    await driver.get('http://127.0.0.1:8000/login');
    // Chờ cho đến khi ô email xuất hiện
    await driver.wait(until.elementLocated(By.name('email')), 10000);
    await driver.findElement(By.name('email')).sendKeys('admin@example.com');
    await driver.findElement(By.name('password')).sendKeys('password', Key.RETURN);
    await driver.wait(until.urlContains('/dashboard'), 10000);
}

describe('Quản lý Giảng viên', function() {
    this.timeout(60000); // Tăng thời gian chờ tối đa cho các test case phức tạp
    let driver;
    // Tạo thông tin duy nhất cho mỗi lần chạy test
    const timestamp = Date.now().toString().slice(-6);
    const newLecturerCode = `GV${timestamp}`;
    const newLecturerName = `Giảng viên Test ${timestamp}`;
    const newLecturerEmail = `gvtest.${timestamp}@example.com`;


    before(async () => {
        const service = new chrome.ServiceBuilder('D:\\chromedriver-win64\\chromedriver.exe'); // Sửa lại đường dẫn nếu cần
        driver = await new Builder().forBrowser('chrome').setChromeService(service).build();
        await loginAsAdmin(driver);
    });

    after(async () => {
        // Có thể thêm logic xóa giảng viên test sau khi chạy xong để dọn dẹp CSDL
        await driver.quit();
    });

    // ... (phần import và các hàm before/after như cũ) ...

it('Nên tạo mới một Giảng viên thành công với đầy đủ thông tin', async () => {
    try {
        // 1. Điều hướng đến trang quản lý giảng viên
        await driver.get('http://127.0.0.1:8000/admin/lecturers');

        // 2. Nhấn nút thêm mới
        await driver.wait(until.elementLocated(By.css("a[href*='/lecturers/create']")), 5000);
        await driver.findElement(By.css("a[href*='/lecturers/create']")).click();
        await driver.wait(until.urlContains('/create'), 5000);

        // 3. Điền form thông tin cơ bản
        await driver.findElement(By.id('lecturer_code')).sendKeys(newLecturerCode);
        await driver.findElement(By.id('full_name')).sendKeys(newLecturerName);
        await driver.findElement(By.id('date_of_birth')).sendKeys('1990-01-15');
        await driver.findElement(By.id('email')).sendKeys(newLecturerEmail);
        
        let genderSelect = await driver.findElement(By.id('gender'));
        await genderSelect.findElement(By.css("option[value='Nam']")).click();
        
        let departmentSelect = await driver.findElement(By.id('department_id'));
        await departmentSelect.findElement(By.css("option[value='1']")).click(); // <<--- ĐẢM BẢO ID KHOA HỢP LỆ
        
        let levelSelect = await driver.findElement(By.id('academic_level'));
        await levelSelect.findElement(By.css("option[value='Thạc sĩ']")).click();
        
        // 4. Điền form Học vị/Học hàm (đầu tiên)
        let degreeTypeSelect = await driver.findElement(By.id('degree_type_id_0'));
        await degreeTypeSelect.findElement(By.css("option[value='2']")).click(); // <<--- ĐẢM BẢO ID LOẠI BẰNG CẤP HỢP LỆ
        await driver.findElement(By.id('degree_specialization_0')).sendKeys('Khoa học Dữ liệu');
        await driver.findElement(By.id('degree_institution_0')).sendKeys('Đại học Test');
        
        // 5. Điền form Quá trình công tác (đầu tiên)
        await driver.findElement(By.id('work_organization')).sendKeys('Công ty Test');
        await driver.findElement(By.id('work_position')).sendKeys('Kỹ sư phần mềm');
        await driver.findElement(By.id('work_start_date')).sendKeys('2020-06-01');

        // 6. Nhấn nút Lưu Giảng viên
        await driver.findElement(By.css('button[type=submit]')).click();

        // 7. KIỂM TRA ĐỂ GỠ LỖI
        // Chờ một chút để trang tải lại sau khi submit
        await driver.sleep(2000); 

        // Lấy URL hiện tại
        const currentUrl = await driver.getCurrentUrl();
        console.log('URL hiện tại sau khi submit:', currentUrl);

        // Nếu URL vẫn là trang 'create', có nghĩa là có lỗi validation
        if (currentUrl.includes('/create')) {
            // Lấy nội dung của div chứa lỗi validation và in ra
            try {
                const errorDiv = await driver.findElement(By.css('div[role=alert]'));
                const errorText = await errorDiv.getText();
                console.log('--- LỖI VALIDATION TÌM THẤY ---');
                console.log(errorText);
                console.log('---------------------------------');
                // Ném lỗi để test case thất bại với thông báo rõ ràng
                throw new Error('Validation failed. See console log for details.');
            } catch (e) {
                // Nếu không tìm thấy div lỗi, có thể có vấn đề khác
                console.log('Không tìm thấy div thông báo lỗi validation. Có thể có lỗi khác.');
                const pageSource = await driver.getPageSource();
                console.log(pageSource.substring(0, 2000)); // In ra một phần source để xem
                throw e;
            }
        }

        // Nếu không có lỗi validation và đã chuyển hướng, kiểm tra như cũ
        await driver.wait(until.urlIs('http://127.0.0.1:8000/admin/lecturers'), 5000);
        
        await driver.wait(until.elementLocated(By.css('div[role=alert]')), 5000);
        const successMessage = await driver.findElement(By.css('div[role=alert]')).getText();
        assert.ok(successMessage.includes('thêm thành công'));
        
        const pageSource = await driver.getPageSource();
        assert.ok(pageSource.includes(newLecturerName), `Không tìm thấy giảng viên mới "${newLecturerName}" trong danh sách.`);

    } catch (error) {
        // In ra lỗi và screenshot để dễ debug
        console.error('Test case thất bại:', error.message);
        await driver.takeScreenshot().then((image, err) => {
            require('fs').writeFileSync(`selenium-tests/error_screenshot_${timestamp}.png`, image, 'base64');
            console.log('Đã lưu ảnh chụp màn hình lỗi vào: selenium-tests/error_screenshot.png');
        });
        // Ném lại lỗi để Mocha biết test case đã fail
        throw error;
    }
});
});