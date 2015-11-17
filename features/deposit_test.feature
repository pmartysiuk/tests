Feature: Deposit page tests

  Scenario: Check deposit view switch button
    Given I am on "/intl/ru/deposit_operations"
     And I wait for ajax
    Then I should see grid view
      And I should not see table view
      And I should see 9 ".trade-depositItem" elements
    When I switch view to "Таблица"
    Then I should see table view
      And I should not see grid view
      And I should see 9 ".trade-depositTableCell.trade-depositTablePS" elements


