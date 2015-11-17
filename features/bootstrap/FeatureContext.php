<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements SnippetAcceptingContext
{
    protected $parameters = [];

    /**
     * @BeforeScenario
     */
    public function windowMaximize()
    {
        $this->getSession()->maximizeWindow();
    }

    /**
     * @When I open :list drop down list
     */
    public function iOpenDropDownList($listName)
    {
        $listSelector = $this->getDropDownListSelector($listName);
        $this->getSession()->getDriver()->click("$listSelector//div[contains(@class, 'ui-selectBtnChoose')]");
    }

    /**
     * @Then :list drop down list should be opened
     */
    public function dropDownListShouldBeOpened($listName)
    {
        $listSelector = $this->getDropDownListSelector($listName);
        $this->assertSession()->elementExists('xpath', "$listSelector//div[@class='ui-selectList']");
    }

    /**
     * @When I choose :value value in :list list
     */
    public function iChooseValue($value, $listName)
    {
        $listSelector = $this->getDropDownListSelector($listName);
        $this->iOpenDropDownList($listName);
        $this->getSession()->getDriver()->click("$listSelector//span[text()='$value']");
    }

    /**
     * @Then :value value should be selected in :list drop down list
     */
    public function valueShouldBeSelectedInDropDownList($value, $listName)
    {
        $listSelector = $this->getDropDownListSelector($listName);
        $this->assertSession()->elementTextContains('xpath', "$listSelector//div[@class='ui-selectItemLabel']", $value);
    }

    private function getDropDownListSelector($listName)
    {
        return ".//*[@id='calculator-mount-node']//div[contains(@class,'calc-colFormLabel') and text()='$listName']/following-sibling::div[contains(@class,'calc-colFormInput')]";
    }

    private function getTotalButton()
    {
        return ".//*[@id='calculator-mount-node']//div[@class='col calc-colFormBtn']/button";
    }

    /**
     * @Then :arg1 text field should contains :arg2 value
     */
    public function textFieldShouldContainsValue($fieldName, $value)
    {
        $actual = $this->getSession()->getDriver()->getValue(".//*[@id='$fieldName']");
        \PHPUnit_Framework_Assert::assertEquals("$value", "$actual");
    }

    /**
     * @When I press total button
     */
    public function iPressTotalButton()
    {
        $this->getSession()->getDriver()->click($this->getTotalButton());
        $this->iWaitForAjaxToFinish();
    }

    /**
     * @When I store conversion pairs value
     */
    public function iStoreConversionPairsValue()
    {
        $this->parameters['conversation_pairs'] = $this->getSession()->getDriver()->getText(".//*[@id='conversion_pairs']//span[@class='calc-pairValue']");
    }

    /**
     * @When I sleep for :arg1 seconds
     */
    public function iSleepForSeconds($seconds)
    {
        sleep($seconds);
    }

    /**
     * @Given I wait for ajax
     */
    public function iWaitForAjaxToFinish()
    {
        $this->getSession()->wait(10000, '(typeof(jQuery)=="undefined" || (0 === jQuery.active && 0 === jQuery(\':animated\').length))');
    }

    /**
     * @Then Margin should be correct
     */
    public function marginShouldBeCorrect()
    {
        $margin = $this->parameters['lot'] * $this->parameters['contract'] / $this->parameters['leverage'] * $this->parameters['conversation_pairs'];
        $margin = number_format((float)$margin, 2, '.', '');
        $this->assertSession()->elementTextContains('xpath', ".//*[@id='margin']", $margin . " USD");
    }

    /**
     * @Then Profit should be correct
     */
    public function profitShouldBeCorrect()
    {
        $profit = $this->parameters['lot'] * $this->parameters['contract'] * $this->parameters['profit_value'];
        $profit = number_format((float)$profit, 2, '.', '');
        $this->assertSession()->elementTextContains('xpath', ".//*[@id='profit']", $profit . " USD");
    }

    /**
     * @Given Swap_long should be correct
     */
    public function swap_longShouldBeCorrect()
    {
        $swap_long = $this->parameters['lot'] * $this->parameters['contract'] * $this->parameters['swap_long'] * $this->parameters['profit_value'];
        $swap_long = number_format((float)$swap_long, 2, '.', '');
        $swap = number_format((float)$this->parameters['swap_long'], 2, '.', '');
        $this->assertSession()->elementTextContains('xpath', ".//*[@id='swap_long']", $swap . " pt. = " . $swap_long . " USD");
    }

    /**
     * @Given Swap_short should be correct
     */
    public function swap_shortShouldBeCorrect()
    {
        $swap_short = $this->parameters['lot'] * $this->parameters['contract'] * $this->parameters['swap_short'] * $this->parameters['profit_value'];
        $swap_short = number_format((float)$swap_short, 2, '.', '');
        $swap = number_format((float)$this->parameters['swap_short'], 2, '.', '');
        $this->assertSession()->elementTextContains('xpath', ".//*[@id='swap_short']", $swap . " pt. = " . $swap_short . " USD");
    }

    /**
     * @Given Volume in usd should be correct
     */
    public function volumemlnusdShouldBeCorrect()
    {
        $volume = $this->parameters['lot'] * $this->parameters['contract'] / 1000000 * $this->parameters['conversation_pairs'];
        $volume = number_format((float)$volume, 2, '.', '');
        $this->assertSession()->elementTextContains('xpath', ".//*[@id='volumemlnusd']", $volume);
    }

    /**
     * @When I set :arg1 as lot
     */
    public function iSetAsLot($lot)
    {
        $this->fillField('id_Lot', $lot);
        $this->parameters['lot'] = $lot;
    }

    /**
     * @Then I should see :arg1 in lot
     */
    public function iShouldSeeInLot($value)
    {
        $this->textFieldShouldContainsValue('id_Lot', $value);
    }

    /**
     * @When I set :arg1 as contract
     */
    public function iSetAsContract($contract)
    {
        $this->parameters['contract'] = $contract;
    }

    /**
     * @When I set :arg1 as leverage
     */
    public function iSetAsLeverage($leverage)
    {
        $this->parameters['leverage'] = $leverage;
    }

    /**
     * @When I set :arg1 as swap_long
     */
    public function iSetAsSwapLong($swap_long)
    {
        $this->parameters['swap_long'] = $swap_long;
    }

    /**
     * @When I set :arg1 as swap_short
     */
    public function iSetAsSwapShort($swap_short)
    {
        $this->parameters['swap_short'] = $swap_short;
    }

    /**
     * @When I set :arg1 as profit_value
     */
    public function iSetAsProfit_value($profit_value)
    {
        $this->parameters['profit_value'] = $profit_value;
    }

    /**
     * @When I seed test data:
     */
    public function iTestDataAre(TableNode $table)
    {
        $array = $table->getHash();
        foreach ($array as $key => $value) {
            foreach ($value as $key => $data) {
                $this->parameters[$key] = $data;
            }
        }
    }

    private $fromFieldSelector = ".//*[@class='converter-tab']//div[@data-reactid='.0.0.1']/input";
    private $toFieldSelector = ".//*[@class='converter-tab']//div[@data-reactid='.0.0.2']/input";

    /**
     * @When I select amount in field
     */
    public
    function iSelectInField()
    {
        $this->getSession()->getDriver()->click($this->fromFieldSelector);
    }

    /**
     * @When I select amount out field
     */
    public function iSelectOutField()
    {
        $this->getSession()->getDriver()->click($this->toFieldSelector);
    }

    /**
     * @When I fill in from field with :arg1
     */
    public function iFillInFromFieldWith($value)
    {
        $this->getSession()->getDriver()->setValue($this->fromFieldSelector, $value);
    }

    /**
     * @When I fill in to field with :arg1
     */
    public
    function iFillInToFieldWith($value)
    {
        $this->getSession()->getDriver()->setValue($this->toFieldSelector, $value);
    }

    /**
     * @When I press clear button
     */
    public function iPressClearButton()
    {
        $this->getSession()->getDriver()->click("//span[text()='Очистить']");
    }

    /**
     * @Then From field should be empty
     */
    public function fromFieldShouldBeEmpty()
    {
        $this->assertSession()->elementAttributeContains('xpath', $this->fromFieldSelector, 'value', "0");
    }

    /**
     * @Then Amount In field should be empty
     */
    public function inFieldShouldBeEmpty()
    {
        $this->assertSession()->elementAttributeContains('xpath', $this->toFieldSelector, 'value', "0");
    }

    /**
     * @When I select popular currency :arg1
     */
    public function iSelectPopularCurrency($currency)
    {
        $popularCurrency = "//ul[@class='converter-popularList']/li[text()='$currency']";
        $this->getSession()->getDriver()->click($popularCurrency);
    }

    /**
     * @Then :arg1 currency should be selected
     */
    public function currencyShouldBeSelected($currency)
    {
        $popularCurrency = "//ul[@class='converter-popularList']/li[text()='$currency']";
        $this->assertSession()->elementAttributeContains('xpath', $popularCurrency, 'class', 'converter-popularItem converter-popularItem__selected');
    }

    /**
     * @Then :arg1 currency should be selected as in currency
     */
    public function currencyShouldBeSelectedAsFromCurrency($currency)
    {
        $fromCurrencySelected = ".//*[@class='converter-tab']//div[@data-reactid='.0.0.1']/span[2]";
        $this->assertSession()->elementTextContains('xpath', $fromCurrencySelected, $currency);
    }

    /**
     * @Then :arg1 currency should be selected as out currency
     */
    public function currencyShouldBeSelectedAsToCurrency($currency)
    {
        $toCurrencySelected = ".//*[@class='converter-tab']//div[@data-reactid='.0.0.2']/span[2]";
        $this->assertSession()->elementTextContains('xpath', $toCurrencySelected, $currency);
    }

    /**
     * @Given I store all currencies
     */
    public function iStoreAllCurrencies()
    {
        $currencyElements = "//div[@class='converter-container' and @data-reactid='.0.2']//li[@class='converter-currenciesItem']/span[@class='converter-currenciesSymbol']";
    }

    /**
     * @When I select currency :arg1 from region list
     */
    public function iSelectCurrencyFromRegionList($currency)
    {
        $regionCurrency = "//div[@class='converter-container' and @data-reactid='.0.2']//li/span[@class='converter-currenciesSymbol' and text()='$currency']";
        $this->getSession()->getDriver()->click($regionCurrency);
    }

    /**
     * @Then :arg1 currency should be selected in region list
     */
    public function currencyShouldBeSelectedInRegionList($currency)
    {
        $selectedCurrency = "//div[@class='converter-container' and @data-reactid='.0.2']//li[contains(@class, 'converter-currenciesItem__selected')]/span[@class='converter-currenciesSymbol' and text()='$currency']";
        $this->assertSession()->elementExists('xpath', $selectedCurrency);
    }

    /**
     * @When I switch view to :arg1
     */
    public function iSwitchViewTo($view)
    {
        $this->getSession()->getDriver()->click("//span[text()='$view']");
        $this->iWaitForAjaxToFinish();
    }

    /**
     * @Then I should see grid view
     */
    public function iShouldSeeGridView()
    {
        $this->assertSession()->elementExists('xpath', "//div[@class='row layout-row trade-deposit trade-depositView']");
    }

    /**
     * @Then I should see table view
     */
    public function iShouldSeeTableView()
    {
        $this->assertSession()->elementExists('xpath', "//div[@class='row layout-row trade-depositView ui-tableMetaContainer']");
    }

    /**
     * @Then I should not see grid view
     */
    public function iShouldNotSeeGridView()
    {
        $this->assertSession()->elementNotExists('xpath', "//div[@class='row layout-row trade-deposit trade-depositView']");
    }

    /**
     * @Then I should not see table view
     */
    public function iShouldNotSeeTableView()
    {
        $this->assertSession()->elementNotExists('xpath', "//div[@class='row layout-row trade-depositView ui-tableMetaContainer']");
    }
}
