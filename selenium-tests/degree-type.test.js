import { Builder, By, until, Key } from 'selenium-webdriver';
import chrome from 'selenium-webdriver/chrome.js';
import assert from 'assert';
import fs from 'fs'; // Import fs để dùng cú pháp ES Module

// Hàm helper để đăng nhập (bạn có thể tách ra file riêng để tái sử dụng)
async function loginAsAdmin(driver) {
    await driver.get('http://127.0.0.1:8000/login');
    await driver.wait(until.elementLocated(By.name('email')), 10000);
    await driver.findElement(By.name('email')).sendKeys('admin@example.com');
    await driver.findElement(By.name('password')).sendKeys('password', Key.RETURN);
    await driver.wait(until.urlContains('/dashboard'), 10000);
}

describe('Quản lý Danh mục Bằng cấp', function() {
    this.timeout(40000); // Tăng thời gian chờ tối đa
    let driver;
    // Tạo thông tin duy nhất cho mỗi lần chạy test
    const timestamp = Date.now().toString().slice(-5);
    const newDegreeTypeName = `Bằng Test Tự Động ${timestamp}`;
    const newDegreeTypeAbbr = `TEST${timestamp}`;

    before(async () => {
        const service = new chrome.ServiceBuilder('D:\\chromedriver-win64\\chromedriver.exe'); // Sửa lại đường dẫn nếu cần
        driver = await new Builder().forBrowser('chrome').setChromeService(service).build();
        await loginAsAdmin(driver);
    });

    after(async () => {
        await driver.quit();
    });

    it('Nên tạo mới một Danh mục Bằng cấp thành công', async () => {
        try {
            // 1. Điều hướng đến trang quản lý danh mục bằng cấp
            await driver.get('http://127.0.0.1:8000/admin/degree-types');

            // 2. Nhấn nút thêm mới
            await driver.wait(until.elementLocated(By.css("a[href*='/degree-types/create']")), 5000);
            await driver.findElement(By.css("a[href*='/degree-types/create']")).click();
            await driver.wait(until.urlContains('/create'), 5000);

            // 3. Điền form (giả sử các trường có id là 'name' và 'abbreviation')
            await driver.findElement(By.id('name')).sendKeys(newDegreeTypeName);
            await driver.findElement(By.id('abbreviation')).sendKeys(newDegreeTypeAbbr);
            await driver.findElement(By.css('button[type=submit]')).click();

            // 4. Kiểm tra kết quả
            await driver.wait(until.urlIs('http://127.0.0.1:8000/admin/degree-types'), 10000);
            const successMessage = await driver.findElement(By.css('div[role=alert]')).getText();
            assert.ok(successMessage.includes('đã được tạo'), 'Thông báo tạo thành công không chính xác.');

            const pageSource = await driver.getPageSource();
            assert.ok(pageSource.includes(newDegreeTypeName), `Không tìm thấy danh mục mới "${newDegreeTypeName}" trong danh sách.`);
        
        } catch (error) {
            // Chụp ảnh màn hình nếu có lỗi để dễ debug
            await driver.takeScreenshot().then((image) => {
                fs.writeFileSync(`selenium-tests/error_create_degree_type_${timestamp}.png`, image, 'base64');
            });
            throw error; // Ném lại lỗi để Mocha biết test case đã fail
        }
    });

    it('Nên xóa một Danh mục Bằng cấp thành công', async () => {
        try {
            // 1. Điều hướng đến trang quản lý
            await driver.get('http://127.0.0.1:8000/admin/degree-types');

            // 2. Tìm dòng chứa danh mục cần xóa và nhấn nút xóa
            const row = await driver.findElement(By.xpath(`//td[contains(text(),"${newDegreeTypeName}")]/parent::tr`));
            await row.findElement(By.css('button[type=submit]')).click();

            // 3. Chấp nhận hộp thoại xác nhận của trình duyệt
            await driver.switchTo().alert().accept();

            // 4. Kiểm tra kết quả
            await driver.wait(until.elementLocated(By.css('div[role=alert]')), 5000);
            const successMessage = await driver.findElement(By.css('div[role=alert]')).getText();
            assert.ok(successMessage.includes('đã được xóa'), 'Thông báo xóa thành công không chính xác.');

            const pageSource = await driver.getPageSource();
            assert.strictEqual(pageSource.includes(newDegreeTypeName), false, `Danh mục "${newDegreeTypeName}" vẫn còn tồn tại sau khi xóa.`);
        
        } catch (error) {
            await driver.takeScreenshot().then((image) => {
                fs.writeFileSync(`selenium-tests/error_delete_degree_type_${timestamp}.png`, image, 'base64');
            });
            throw error;
        }
    });
});