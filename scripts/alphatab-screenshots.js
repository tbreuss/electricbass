import puppeteer from "puppeteer";

const token = process.argv[2] ?? "";

async function takeScreenshot(url) {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();

    await page.setViewport({
        width: 1800,
        height: 3600,
        deviceScaleFactor: 2, // retina
    });

    await page.goto(url, { waitUntil: "networkidle2" });

    await page.screenshot({
        path: "screenshots/page.webp",
        type: "webp",
    });

    const elements = await page.$$('.atw-notation');
    for(const element of elements ) {
        const uid = await page.evaluate(el => el.getAttribute("data-uid"), element);
        await element.scrollIntoView();
        await element.screenshot({
            path: "screenshots/" + uid + ".webp",
            type: "webp",
        });
        console.log(uid);
    }

    await browser.close();
}

takeScreenshot("http://www.electricbass.test/alpha-tab/debug?key=" + token);
