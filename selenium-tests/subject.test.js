import { Builder, By, until, Key } from 'selenium-webdriver';
import chrome from 'selenium-webdriver/chrome.js';
import assert from 'assert';
import fs from 'fs';

// Hàm helper để đăng nhập (bạn có thể tách ra file riêng để tái sử dụng)
async function loginAsAdmin(driver) {
    await driver.get('http://127.0.0.1:8000/login');
    await driver.wait(until.elementLocated(By.name('email')), 10000);
    await driver.findElement(By.name('email')).sendKeys('admin@example.com');
    await driver.findElement(By.name('password')).sendKeys('password', Key.RETURN);
    await driver.wait(until.urlContains('/dashboard'), 10000);
}

describe('Quản lý Học phần', function() {
    this.timeout(40000);
    let driver;
    // Tạo thông tin duy nhất cho mỗi lần chạy test
    const timestamp = Date.now().toString().slice(-6);
    const newSubjectCode = `TEST${timestamp}`;
    const newSubjectName = `Học phần Test Tự động ${timestamp}`;

    before(async () => {
        const service = new chrome.ServiceBuilder('D:\\chromedriver-win64\\chromedriver.exe'); // Sửa lại đường dẫn nếu cần
        driver = await new Builder().forBrowser('chrome').setChromeService(service).build();
        await loginAsAdmin(driver);
    });

    after(async () => {
        await driver.quit();
    });

    it('TC_SUBJECT_01: Nên tạo mới một Học phần thành công', async () => {
        try {
            // 1. Điều hướng đến trang quản lý học phần
            await driver.get('http://127.0.0.1:8000/admin/subjects');
            await driver.findElement(By.css("a[href*='/subjects/create']")).click();
            await driver.wait(until.urlContains('/create'), 5000);

            // 2. Điền form
            await driver.findElement(By.id('subject_code')).sendKeys(newSubjectCode);
            await driver.findElement(By.id('name')).sendKeys(newSubjectName);
            await driver.findElement(By.id('credits')).sendKeys('3');
            await driver.findElement(By.id('default_teaching_hours')).sendKeys('45');
            await driver.findElement(By.id('subject_coefficient')).sendKeys('1.2');
            
            // Chọn Khoa (giả sử Khoa có id=1 tồn tại)
            let departmentSelect = await driver.findElement(By.id('department_id'));
            await departmentSelect.findElement(By.css("option[value='1']")).click();

            await driver.findElement(By.id('description')).sendKeys('Mô tả cho học phần test tự động.');

            await driver.findElement(By.css('button[type=submit]')).click();

            // 3. Kiểm tra kết quả
            await driver.wait(until.urlIs('http://127.0.0.1:8000/admin/subjects'), 10000);
            const successMessage = await driver.findElement(By.css('div[role=alert]')).getText();
            assert.ok(successMessage.includes('Học phần đã được tạo thành công.'), 'Thông báo tạo học phần không chính xác.');

            const pageSource = await driver.getPageSource();
            assert.ok(pageSource.includes(newSubjectName), `Không tìm thấy học phần mới "${newSubjectName}" trong danh sách.`);
        
        } catch (error) {
            await driver.takeScreenshot().then((image) => {
                fs.writeFileSync(`selenium-tests/error_create_subject_${timestamp}.png`, image, 'base64');
            });
            throw error;
        }
    });

    it('TC_SUBJECT_02: Nên thất bại khi tạo Học phần có Mã đã tồn tại', async () => {
        try {
            // 1. Điều hướng đến trang thêm mới học phần
            await driver.get('http://127.0.0.1:8000/admin/subjects/create');
            await driver.wait(until.elementLocated(By.id('subject_code')), 5000);
            
            // 2. Điền form với mã học phần đã được tạo ở test case trước
            await driver.findElement(By.id('subject_code')).sendKeys(newSubjectCode);
            await driver.findElement(By.id('name')).sendKeys('Tên học phần trùng lặp');
            await driver.findElement(By.id('credits')).sendKeys('2');
            await driver.findElement(By.id('default_teaching_hours')).sendKeys('30');
            await driver.findElement(By.id('subject_coefficient')).sendKeys('1.0');
            await driver.findElement(By.css('button[type=submit]')).click();
            
            // 3. Kiểm tra kết quả
            await driver.wait(until.elementLocated(By.css('div[role=alert]')), 5000);
            const errorDiv = await driver.findElement(By.css('div[role=alert]'));
            const errorText = await errorDiv.getText();
            assert.ok(errorText.includes('subject code has already been taken'), 'Không hiển thị lỗi validation cho mã học phần trùng lặp.');
        
        } catch (error) {
            await driver.takeScreenshot().then((image) => {
                fs.writeFileSync(`selenium-tests/error_duplicate_subject_${timestamp}.png`, image, 'base64');
            });
            throw error;
        }
    });

    it('TC_SUBJECT_03: Nên xóa một Học phần thành công', async () => {
        try {
            // 1. Điều hướng đến trang quản lý
            await driver.get('http://127.0.0.1:8000/admin/subjects');

            // 2. Tìm dòng chứa học phần cần xóa và nhấn nút xóa
            const row = await driver.findElement(By.xpath(`//td[contains(text(),'${newSubjectCode}')]/parent::tr`));
            await row.findElement(By.css('button[type=submit]')).click();

            // 3. Chấp nhận hộp thoại xác nhận
            await driver.switchTo().alert().accept();

            // 4. Kiểm tra kết quả
            await driver.wait(until.elementLocated(By.css('div[role=alert]')), 5000);
            const successMessage = await driver.findElement(By.css('div[role=alert]')).getText();
            assert.ok(successMessage.includes('Học phần đã được xóa thành công.'), 'Thông báo xóa học phần không chính xác.');

            const pageSource = await driver.getPageSource();
            assert.strictEqual(pageSource.includes(newSubjectName), false, `Học phần "${newSubjectName}" vẫn còn tồn tại sau khi xóa.`);
        
        } catch (error) {
            await driver.takeScreenshot().then((image) => {
                fs.writeFileSync(`selenium-tests/error_delete_subject_${timestamp}.png`, image, 'base64');
            });
            throw error;
        }
    });
});