<?php

//autoload function
function __autoload($class_name) {
   // Start from the base path and determine the location from the class name,
   $base_path = 'php-ews';
   $include_file = $base_path . '/' . str_replace('_', '/', $class_name) . '.php';

   return (file_exists($include_file) ? require_once $include_file : false);
}

//autoload function doesn't handle ews_exception correctly - manually add that
require_once 'php-ews/EWS_Exception.php';

class getUserAvailability {
   var $username = 'username@mydomain.com';                  //user with access to ews and shared calendars
   var $password = 'mySuperSecretPassword';                  //password for the user
   var $ewsServer = 'myewsServer.mydomain.com';              //server name for ews
   
   function __construct() {
      $this->ews = new ExchangeWebServices($this->ewsServer, $this->username, $this->password, ExchangeWebServices::VERSION_2010_SP1);
   }
   
   function getAvailability($user, $startDate, $endDate) {
      $request = new EWSType_GetUserAvailabilityRequestType();

      $request->TimeZone = new EWSType_SerializableTimeZone();
      $request->TimeZone->Bias = '480';
      $request->TimeZone->StandardTime = new EWSType_SerializableTimeZoneTime();
      $request->TimeZone->StandardTime->Bias = '0';
      $request->TimeZone->StandardTime->Time = '02:00:00';
      $request->TimeZone->StandardTime->DayOrder = '5';
      $request->TimeZone->StandardTime->Month = '1';
      $request->TimeZone->StandardTime->DayOfWeek = 'Sunday';
      $request->TimeZone->DaylightTime = new EWSType_SerializableTimeZoneTime();
      $request->TimeZone->DaylightTime->Bias = '-60';
      $request->TimeZone->DaylightTime->Time = '02:00:00';
      $request->TimeZone->DaylightTime->DayOrder = '1';
      $request->TimeZone->DaylightTime->Month = '4';
      $request->TimeZone->DaylightTime->DayOfWeek = 'Sunday';

      $request->MailboxDataArray = new EWSType_ArrayOfMailboxData();
      $request->MailboxDataArray->MailboxData = new EWSType_MailboxData();
      $request->MailboxDataArray->MailboxData->Email = new EWSType_EmailAddressType();
      $request->MailboxDataArray->MailboxData->Email->Address = $user;
      $request->MailboxDataArray->MailboxData->Email->RoutingType = 'SMTP';
      $request->MailboxDataArray->MailboxData->AttendeeType = 'Required';
      $request->MailboxDataArray->MailboxData->ExcludeConflicts = false;
      $request->FreeBusyViewOptions = new EWSType_FreeBusyViewOptionsType();
      $request->FreeBusyViewOptions->TimeWindow = new EWSType_Duration();
      $request->FreeBusyViewOptions->TimeWindow->StartTime = $startDate;
      $request->FreeBusyViewOptions->TimeWindow->EndTime = $endDate;
      $request->FreeBusyViewOptions->MergedFreeBusyIntervalInMinutes = '30';
      $request->FreeBusyViewOptions->RequestedView = 'Detailed';
      $response = $this->ews->GetUserAvailability($request);
      
      $returnValue = array();

      if ($response->FreeBusyResponseArray->FreeBusyResponse->ResponseMessage->ResponseClass == "Success"){
         if (property_exists($response->FreeBusyResponseArray->FreeBusyResponse->FreeBusyView, 'CalendarEventArray')) {
            $events = $response->FreeBusyResponseArray->FreeBusyResponse->FreeBusyView->CalendarEventArray->CalendarEvent;
            $returnValue['events'] = $events;
         }
      }

      $workingPeriod = $response->FreeBusyResponseArray->FreeBusyResponse->FreeBusyView->WorkingHours->WorkingPeriodArray->WorkingPeriod;
      $returnValue['workingPeriod'] = $workingPeriod;
      
      return $returnValue;
   }
}

?>