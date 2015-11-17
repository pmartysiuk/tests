Feature: Calculator tests

  Background:
    Given I am on "/intl/ru/tools/calculator"
    And I wait for ajax

  Scenario Outline: Check that it's possible to open dropDown lists
    When I open "<drop_down>" drop down list
    Then "<drop_down>" drop down list should be opened
  Examples:
    | drop_down       |
    | Тип счета       |
    | Биржа           |
    | Форекс          |
    | Кредитное плечо |
    | Валюта счета    |

  Scenario Outline: Select a value from lists
    When I choose "<value>" value in "<drop_down>" list
    Then "<value>" value should be selected in "<drop_down>" drop down list

  Examples:
    | drop_down       | value                      |
    | Тип счета       | Cent                       |
    | Форекс          | AUDCHF                     |
    | Кредитное плечо | 1:1000                     |
    | Валюта счета    | AUD - Австралийский доллар |
    | Тип счета       | Common                     |

  Scenario: Select a value from list NYMEX and "Биржа"
    When I choose "Future Specifications" value in "Биржа" list
    Then "Future Specifications" value should be selected in "Биржа" drop down list
    When I choose "CLZ5" value in "NYMEX" list
    Then "CLZ5" value should be selected in "NYMEX" drop down list

  Scenario: Check Lot field to be selectable and editable
    When I set "1000" as lot
    Then I should see "1000" in lot

  Scenario: Check calculations with different parameters
    When I choose "EURUSD" value in "Форекс" list
      And I choose "USD - Американский доллар" value in "Валюта счета" list
      And I press total button
      And I store conversion pairs value
      And I seed test data:
        | lot | contract | leverage | swap_long | swap_short | profit_value |
        | 0.1 | 100000   | 100      | -0.13840  | 0          | 0.00010      |
    Then Margin should be correct
      And Profit should be correct
      And Swap_long should be correct
      And Swap_short should be correct
      And Volume in usd should be correct
    When I set "10" as lot
      And I press total button
    Then Margin should be correct
      And Profit should be correct
      And Swap_long should be correct
      And Swap_short should be correct
      And Volume in usd should be correct

  Scenario: Check that converter tab works
    When I follow "Конвертер валют"
    Then I should be on "https://www.exness.com/intl/ru/tools/converter/"
