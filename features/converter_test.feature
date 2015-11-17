Feature: Converter test

  Background:
    Given I am on "/intl/ru/tools/converter"
      And I wait for ajax

  Scenario Outline: Check that it's possible to select currencies in the popular section
    When I select amount in field
      And I select popular currency "<in_currency>"
    Then "<in_currency>" currency should be selected
      And "<in_currency>" currency should be selected as in currency
    When I select amount out field
      And I select popular currency "<out_currency>"
    Then "<out_currency>" currency should be selected
      And "<out_currency>" currency should be selected as out currency

  Examples:
    | in_currency | out_currency |
    | USD         | EUR          |
    | EUR         | CHF          |
    | CHF         | JPY          |
    | JPY         | AUD          |
    | AUD         | CAD          |
    | CAD         | USD          |

  Scenario Outline: Check that currencies in region list are clickable
    When I select amount in field
      And I select currency "<in_currency>" from region list
    Then "<in_currency>" currency should be selected in region list
      And "<in_currency>" currency should be selected as in currency
    When I select amount out field
      And I select currency "<out_currency>" from region list
    Then "<out_currency>" currency should be selected in region list
      And "<out_currency>" currency should be selected as out currency

  Examples:
    | in_currency | out_currency |
    | AED         | ARS          |
    | MAD         | AED          |
    | BGN         | MAD          |
    | ARS         | BGN          |

  Scenario: Check clear button work
    When I select amount in field
      And I fill in from field with "100"
      And I press clear button
    Then From field should be empty
      And Amount In field should be empty

