const puppeteer = require('puppeteer');

(async () => {
  // Запуск браузера
  const browser = await puppeteer.launch({ headless: true }); // headless: true для запуска без GUI
  const page = await browser.newPage();

  try {
    // 1) Перейти на сайт index.php
    await page.goto('http://localhost/m_wishlist/index.php');

    // 2) Заполнить поле name как "autotest"
    await page.type('#name', 'autotest');

    // 3) Заполнить идентификатор как "autotest"
    await page.type('#wishlist_id', 'autotest');

    // 4) Заполнить пароль как "autotest"
    await page.type('#password', 'autotest');

    // 5) Нажать кнопку "Создать вишлист"
    await page.click('button[type="submit"]');

    // Ждем перенаправления на страницу create.php
    await page.waitForNavigation();

    // 6) Проверяем URL и содержимое страницы
    const currentUrl = page.url();
    if (currentUrl !== 'http://localhost/m_wishlist/create.php') {
      throw new Error(`Ожидался URL http://localhost/m_wishlist/create.php, но получен ${currentUrl}`);
    }

    const bodyContent = await page.evaluate(() => document.body.textContent);
    if (!bodyContent.includes('Вишлист успешно создан!')) {
      throw new Error('Текст "Вишлист успешно создан!" не найден на странице.');
    }

    console.log('Тест пройден успешно!');
  } catch (error) {
    console.error('Тест не пройден:', error.message);
    process.exit(1); // Завершаем процесс с ошибкой
  } finally {
    await browser.close();
  }
})();