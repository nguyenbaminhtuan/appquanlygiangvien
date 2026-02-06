import { Builder, By, until } from 'selenium-webdriver';
import chrome from 'selenium-webdriver/chrome.js';
import assert from 'assert';

describe('Laravel Login Test', function() {
  // Tăng thời gian chờ tối đa cho mỗi test case
  this.timeout(30000);

  let driver;

  // Hàm này chạy một lần trước khi tất cả các test bắt đầu
  before(async () => {
    const chromeDriverPath = 'D:\\chromedriver-win64\\chromedriver.exe';
    const service = new chrome.ServiceBuilder(chromeDriverPath);

    driver = await new Builder()
      .forBrowser('chrome')
      .setChromeService(service) // Chỉ định tường minh vị trí chromedriver.exe
      .build();
  });

  // Hàm này chạy một lần sau khi tất cả các test kết thúc
  after(async () => {
    await driver.quit();
  });

  // Test case: Đăng nhập thành công
  // Test case: Đăng nhập thành công
// Test case: Đăng nhập thành công
it('Nên đăng nhập thành công và hiển thị đúng trang dashboard', async () => {
  // 1. Điều hướng đến trang đăng nhập
  await driver.get('http://127.0.0.1:8000/login');

  // 2. Tìm các trường và điền thông tin đăng nhập
  await driver.findElement(By.name('email')).sendKeys('admin@example.com');
  await driver.findElement(By.name('password')).sendKeys('password');

  // 3. Tìm và nhấn vào nút đăng nhập
  await driver.findElement(By.css('button[type=submit]')).click();

  // 4. Chờ cho đến khi URL chứa '/dashboard'
  await driver.wait(until.urlContains('/dashboard'), 10000);
  // Phương pháp này sẽ tìm trong toàn bộ nội dung trang.
  let bodyText = await driver.findElement(By.tagName('body')).getText();
  
  // Chúng ta chỉ cần kiểm tra có cụm từ "Chào mừng trở lại" là đủ để xác nhận.
  assert.strictEqual(
    bodyText.includes('Chào mừng trở lại'), 
    true, 
    "Không tìm thấy lời chào mừng 'Chào mừng trở lại' sau khi đăng nhập."
  );
});
});