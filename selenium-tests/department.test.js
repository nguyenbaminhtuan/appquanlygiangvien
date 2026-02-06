import { Builder, By, until, Key } from 'selenium-webdriver';
import chrome from 'selenium-webdriver/chrome.js';
import assert from 'assert';

// Hàm helper để đăng nhập (có thể đặt trong một file riêng và import)
async function loginAsAdmin(driver) {
    await driver.get('http://127.0.0.1:8000/login');
    await driver.findElement(By.name('email')).sendKeys('admin@example.com');
    await driver.findElement(By.name('password')).sendKeys('password', Key.RETURN); // Gửi phím Enter để submit
    await driver.wait(until.urlContains('/dashboard'), 10000);
}

describe('Quản lý Khoa', function() {
    this.timeout(40000);
    let driver;
    const newDepartmentCode = `TEST${Date.now().toString().slice(-5)}`;
    const newDepartmentName = `Khoa Test Tự Động ${newDepartmentCode}`;

    before(async () => {
        const service = new chrome.ServiceBuilder('D:\\chromedriver-win64\\chromedriver.exe');
        driver = await new Builder().forBrowser('chrome').setChromeService(service).build();
        await loginAsAdmin(driver);
    });

    after(async () => {
        await driver.quit();
    });

    it('Nên tạo mới một Khoa thành công và hiển thị trong danh sách', async () => {
        // 1. Điều hướng đến trang quản lý khoa
        await driver.get('http://127.0.0.1:8000/admin/departments');

        // 2. Nhấn nút thêm mới
        const addButton = await driver.findElement(By.css("a[href*='/departments/create']"));
await addButton.click();

await driver.wait(until.urlContains('/create'), 5000);

        // 3. Điền form
        await driver.findElement(By.name('code')).sendKeys(newDepartmentCode);
        await driver.findElement(By.name('name')).sendKeys(newDepartmentName);
        await driver.findElement(By.css('button[type=submit]')).click();

        // 4. Kiểm tra kết quả
        await driver.wait(until.urlIs('http://127.0.0.1:8000/admin/departments'), 10000);
        const successMessage = await driver.findElement(By.css('div[role=alert]')).getText();
        assert.ok(successMessage.includes('tạo thành công'));

        const pageSource = await driver.getPageSource();
        assert.ok(pageSource.includes(newDepartmentName), `Không tìm thấy khoa mới "${newDepartmentName}" trong danh sách.`);
    });

    it('Nên xóa một Khoa thành công', async () => {
        // 1. Điều hướng đến trang quản lý khoa
        await driver.get('http://127.0.0.1:8000/admin/departments');

        // 2. Tìm dòng chứa khoa cần xóa và nhấn nút xóa
        // Tìm dòng (tr) chứa text của khoa mới, sau đó tìm nút xóa trong dòng đó
        const row = await driver.findElement(By.xpath(`//td[contains(text(),'${newDepartmentName}')]/parent::tr`));
        await row.findElement(By.css('button[type=submit]')).click();

        // 3. Chấp nhận hộp thoại xác nhận
        await driver.switchTo().alert().accept();

        // 4. Kiểm tra kết quả
        await driver.wait(until.elementLocated(By.css('div[role=alert]')), 5000);
        const successMessage = await driver.findElement(By.css('div[role=alert]')).getText();
        assert.ok(successMessage.includes('xóa thành công'));

        const pageSource = await driver.getPageSource();
        assert.strictEqual(pageSource.includes(newDepartmentName), false, `Khoa "${newDepartmentName}" vẫn còn tồn tại sau khi xóa.`);
    });
});