<?php
defined('BASEPATH') OR exit('No direct script access allowed');



$route['default_controller'] = 'Auth/index';
$route['404_override'] = 'Auth/get404';
$route['translate_uri_dashes'] = FALSE;


//login
$route['login'] = 'Auth/onSetLogin';
$route['chk_login'] = 'Auth/onCheckLogin';
$route['chklogin2fa'] = 'Auth/onCheck2FAuth';
$route['logout'] = 'Auth/onSetLogout';
$route['dashboard'] = 'Auth/onGetDashboard';
$route['send-password-recovery'] = 'Auth/onSendPasswordRecovery';

//user management
$route['users'] = 'Users/index';
$route['duplicate_check_un'] = 'Users/onCheckDuplicateUser';
$route['add-user'] = 'Users/onCreateUserView';
$route['createuser'] = 'Users/onCreateUser';
$route['profile'] = 'Users/onGetUserProfile/';
$route['profile/(:any)'] = 'Users/onGetUserProfile/$1';
$route['changeprofile'] = 'Users/onChangeUserProfile';
$route['deluser'] = 'Users/onDeleteUser';
$route['enable2fa'] = 'Users/onGetTwoFACode';
$route['set2fa'] = 'Users/onSet2FAuth';

//customer management
$route['customers'] = 'Customers/index';
$route['duplicate_check_cust'] = 'Customers/onCheckDuplicateCust';
$route['add-customer'] = 'Customers/onCreateCustView';
$route['createcustomer'] = 'Customers/onCreateCust';
$route['edit-customer/(:any)'] = 'Customers/onGetCustEdit/$1';
$route['changecustomer'] = 'Customers/onChangeCust';
$route['delcustomer'] = 'Customers/onDeleteCust';
$route['customer-kyc-details'] = 'Customers/onGetCustomerKyc';
$route['ack-customer-kyc'] = 'Customers/onAckCustomerKyc';

//it projects management
$route['it-projects'] = 'ITProjects/index';
$route['duplicate_check_project'] = 'ITProjects/onCheckDuplicateProject';
$route['add-it-project'] = 'ITProjects/onCreateProjectView';
$route['createproject'] = 'ITProjects/onCreateProject';
$route['edit-project/(:any)'] = 'ITProjects/onGetProjectEdit/$1';
$route['changeproject'] = 'ITProjects/onChangeProject';
$route['delproject'] = 'ITProjects/onDeleteProject';
$route['applied-it-projects/(:any)'] = 'ITProjects/onGetAppliedProject/$1';

$route['monthly-payout-it-projects'] = 'ITProjects/onGetPayoutITProject';

//investment plans management
$route['investment-plans'] = 'InvestmentPlans/index';
$route['duplicate_check_ip'] = 'InvestmentPlans/onCheckDuplicateInvPlan';
$route['add-investment-plan'] = 'InvestmentPlans/onCreateInvPlanView';
$route['createinvplan'] = 'InvestmentPlans/onCreateInvPlan';
$route['edit-investment-plan/(:any)'] = 'InvestmentPlans/onGetInvPlanEdit/$1';
$route['changeinvplan'] = 'InvestmentPlans/onChangeInvPlan';
$route['delinvplan'] = 'InvestmentPlans/onDeleteInvPlan';
$route['applied-investment-plans/(:any)'] = 'InvestmentPlans/onGetAppliedInvPlan/$1';

//cryptocurrency management
$route['view-cryptocurrency'] = 'Crypto/onGetCryptoDetails';
$route['update-cryptocurrency'] = 'Crypto/onSetCryptoDetails';

$route['buy-cryptocurrency-transactions'] = 'Crypto/onGetCryptoTransactionsBuy';
$route['approve-buy-req'] = 'Crypto/onApproveBuyReq';

$route['sell-cryptocurrency-transactions'] = 'Crypto/onGetCryptoTransactionsSell';
$route['approve-sell-req'] = 'Crypto/onApproveSellReq';


//wallet
$route['wallet-redeem-requests'] = 'Wallet/onGetWalletRedeemRequests';
$route['approve-wallet-req'] = 'Wallet/onApproveWalletRedeemReq';


$route['wallet-withdrawal-requests'] = 'Wallet/onGetWalletWithdrawalRequests';
$route['approve-withdrawal-req'] = 'Wallet/onApproveWalletWithdrawalReq';
$route['withdrawal-pay-now'] = 'Wallet/onPayNowWithdrawal';

$route['pay-now/(:any)'] = 'Payment/onPayNow/$1';
$route['setpayment'] = 'Payment/onSavePayment';

//others

$route['view-privacy-tnc'] = 'Info/onGetPrivacyDetails';
$route['update-privacy-tnc'] = 'Info/onSetPrivacyDetails';

//Api
$route['api/encrypt-this'] = 'Api/onEncryptAll';
$route['api/decrypt-all'] = 'Api/onDecryptAll';//


$route['api/register'] = 'Api/onRegisterCustomer';
$route['api/registered-otp-verification'] = 'Api/onVerifyCustomer';
$route['api/update-kyc'] = 'Api/onKycCustomer';
$route['api/change-password'] = 'Api/onChangePassword';
$route['api/users-list'] = 'Api/onGetCustomers';
$route['api/login'] = 'Api/onLoginCustomer';
$route['api/logout'] = 'Api/onLogoutCustomer';
$route['api/login-old'] = 'Api/onLoginCustomer_old';
$route['api/verify-login'] = 'Api/onVerifyCustomerLogin';
$route['api/update-profile'] = 'Api/onUpdateCustomerProfile';

$route['api/it-projects'] = 'Api/onGetITProjects';
$route['api/user-buy-it-project'] = 'Api/onCustomerBuyITProject';
$route['api/it-projects-bought-by-user'] = 'Api/onCustomerBoughtITProjects';

$route['api/investment-plans'] = 'Api/onGetInvPlans';
$route['api/user-buy-investment-plan'] = 'Api/onCustomerBuyInvPlan';
$route['api/investment-plans-bought-by-user'] = 'Api/onCustomerBoughtInvPlans';

$route['api/cryptocurrency-list'] = 'Api/onGetCryptoList';
$route['api/user-buy-cryptocurrency'] = 'Api/onCustomerBuyCrypto';
$route['api/cryptocurrency-bought-by-user'] = 'Api/onCustomerBoughtCrypto';
$route['api/user-sell-cryptocurrency'] = 'Api/onCustomerSellCrypto';
$route['api/cryptocurrency-wallet'] = 'Api/onCryptoWallet';
$route['api/cryptocurrency-transactions'] = 'Api/onCryptoTransactions';

$route['api/redeem-request'] = 'Api/onCustomerRedeemRequest';
$route['api/withdrawal-request'] = 'Api/onCustomerWithdrawalRequest';
$route['api/privacy-and-tnc'] = 'Api/onGetPrivacyTnC';
$route['api/notification-list'] = 'Api/onGetNotificationLogs';

$route['api/payment-keys'] = 'Api/onGetPaymentKeys';