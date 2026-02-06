import { Builder, By, until, Key } from 'selenium-webdriver';
import chrome from 'selenium-webdriver/chrome.js';
import assert from 'assert';
import fs from 'fs';

// Hàm helper để đăng nhập (có thể tách ra file riêng để tái sử dụng)
async function loginAsAdmin(driver) {
    await driver.get('http://127.0.0.1:8000/login');
    await driver.wait(until.elementLocated(By.name('email')), 10000);
    await driver.findElement(By.name('email')).sendKeys('admin@example.com');
    await driver.findElement(By.name('password')).sendKeys('password', Key.RETURN);
    await driver.wait(until.urlContains('/dashboard'), 10000);
}

describe('Quản lý Năm học và Kì học', function() {
    this.timeout(60000);
    let driver;
    const currentYear = new Date().getFullYear();
    const newAcademicYearName = `Năm học Test ${currentYear}-${currentYear + 1}`;
    const newSemesterName = 'Học kì Test';

    // ... (hàm before và after như cũ) ...

    it('TC_ACADEMIC_YEAR_01: Nên tạo mới một Năm học thành công', async () => {
        try {
            // 1. Điều hướng đến trang quản lý năm học
            await driver.get('http://127.0.0.1:8000/admin/academic-years/create');
            await driver.wait(until.elementLocated(By.id('name')), 5000);

            // 2. Điền form
            await driver.findElement(By.id('name')).sendKeys(newAcademicYearName);

            // SỬA LỖI: Dùng JavaScript để đặt giá trị cho input date
            const startDate = `${currentYear}-09-05`;
            const endDate = `${currentYear + 1}-06-30`;
            await driver.executeScript(`document.getElementById('start_date').value = '${startDate}'`);
            await driver.executeScript(`document.getElementById('end_date').value = '${endDate}'`);

            await driver.findElement(By.css('button[type=submit]')).click();

            // 3. Kiểm tra kết quả
            await driver.wait(until.urlIs('http://127.0.0.1:8000/admin/academic-years'), 10000, 'Không chuyển hướng về trang danh sách Năm học sau khi lưu.');
            
            const successMessage = await driver.findElement(By.css('div[role=alert]')).getText();
            assert.ok(successMessage.includes('Năm học đã được tạo thành công.'), 'Thông báo tạo năm học không chính xác.');

            const pageSource = await driver.getPageSource();
            assert.ok(pageSource.includes(newAcademicYearName), `Không tìm thấy năm học mới "${newAcademicYearName}" trong danh sách.`);

        } catch (error) {
            console.error('Test case "Tạo Năm học" thất bại:', error.message);
            await driver.takeScreenshot().then((image) => {
                fs.writeFileSync(`selenium-tests/error_create_academic_year.png`, image, 'base64');
                console.log('Đã lưu ảnh chụp màn hình lỗi vào: selenium-tests/error_create_academic_year.png');
            });
            throw error;
        }
    });

    it('TC_SEMESTER_01: Nên tạo mới một Kì học thành công cho Năm học vừa tạo', async () => {
        // 1. Điều hướng đến trang quản lý kì học
        await driver.get('http://127.0.0.1:8000/admin/semesters');
        await driver.findElement(By.css("a[href*='/semesters/create']")).click();
        await driver.wait(until.urlContains('/create'), 5000);

        // 2. Điền form và chọn Năm học đã tạo
        let academicYearSelect = await driver.findElement(By.id('academic_year_id'));
        // Tìm option có chứa text của năm học mới
        await academicYearSelect.findElement(By.xpath(`//option[contains(text(),'${newAcademicYearName}')]`)).click();
        
        await driver.findElement(By.id('name')).sendKeys(newSemesterName);
        await driver.findElement(By.id('start_date')).sendKeys(`${yearTimestamp}-09-05`);
        await driver.findElement(By.id('end_date')).sendKeys(`${yearTimestamp + 1}-01-15`);
        await driver.findElement(By.css('button[type=submit]')).click();

        // 3. Kiểm tra kết quả
        await driver.wait(until.urlIs('http://127.0.0.1:8000/admin/semesters'), 10000);
        const successMessage = await driver.findElement(By.css('div[role=alert]')).getText();
        assert.ok(successMessage.includes('Kì học đã được tạo thành công.'), 'Thông báo tạo kì học không chính xác.');

        const pageSource = await driver.getPageSource();
        assert.ok(pageSource.includes(newSemesterName) && pageSource.includes(newAcademicYearName), `Không tìm thấy kì học mới "${newSemesterName}" trong danh sách.`);
    });
    
    it('TC_ACADEMIC_YEAR_02: Nên thất bại khi xóa Năm học vẫn còn Kì học', async () => {
        // 1. Điều hướng đến trang quản lý năm học
        await driver.get('http://127.0.0.1:8000/admin/academic-years');

        // 2. Tìm và xóa năm học đã tạo
        const row = await driver.findElement(By.xpath(`//td[contains(text(),'${newAcademicYearName}')]/parent::tr`));
        await row.findElement(By.css('button[type=submit]')).click();
        await driver.switchTo().alert().accept();

        // 3. Kiểm tra kết quả
        await driver.wait(until.elementLocated(By.css('div[role=alert]')), 5000);
        const errorMessage = await driver.findElement(By.css('div[role=alert]')).getText();
        assert.ok(errorMessage.includes('Không thể xóa năm học này vì vẫn còn các kì học trực thuộc.'), 'Thông báo lỗi khi xóa năm học không chính xác.');
    });

    it('TC_ACADEMIC_YEAR_03: Nên xóa thành công Năm học sau khi đã xóa Kì học', async () => {
        // 1. Xóa Kì học trước
        await driver.get('http://127.0.0.1:8000/admin/semesters');
        const semesterRow = await driver.findElement(By.xpath(`//td[contains(text(),'${newSemesterName}')]/parent::tr`));
        await semesterRow.findElement(By.css('button[type=submit]')).click();
        await driver.switchTo().alert().accept();
        await driver.wait(until.elementLocated(By.css('div[role=alert]')), 5000); // Chờ thông báo xóa kì học

        // 2. Quay lại trang Năm học và xóa
        await driver.get('http://127.0.0.1:8000/admin/academic-years');
        const academicYearRow = await driver.findElement(By.xpath(`//td[contains(text(),'${newAcademicYearName}')]/parent::tr`));
        await academicYearRow.findElement(By.css('button[type=submit]')).click();
        await driver.switchTo().alert().accept();

        // 3. Kiểm tra kết quả
        await driver.wait(until.elementLocated(By.css('div[role=alert]')), 5000);
        const successMessage = await driver.findElement(By.css('div[role=alert]')).getText();
        assert.ok(successMessage.includes('Năm học đã được xóa thành công.'), 'Thông báo xóa năm học không chính xác.');

        const pageSource = await driver.getPageSource();
        assert.strictEqual(pageSource.includes(newAcademicYearName), false, 'Năm học vẫn còn tồn tại sau khi xóa.');
    });
});