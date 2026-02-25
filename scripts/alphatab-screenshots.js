import puppeteer from "puppeteer";

async function takeScreenshot(url) {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();

    await page.setViewport({
        width: 1366,
        height: 2000,
        deviceScaleFactor: 2, // retina
    });

    await page.goto(url, { waitUntil: "networkidle2" });

    await page.screenshot({
        path: "page.png",
    });

    const element = await page.$(".atw");
    await element.screenshot({ path: "element.png" });

    await browser.close();
}

takeScreenshot("http://electricbass.test/lektionen/bassriff/1793");
