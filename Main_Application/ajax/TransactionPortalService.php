<?php
class ArchiveResponseRequest {
  public $User; // string
  public $Pass; // string
  public $Dsn; // string
  public $ResponseId; // long
}

class ResponseRequest {
  public $User; // string
  public $Pass; // string
  public $Dsn; // string
  public $OrigTraceNumber; // string
  public $EmedixTraceNumber; // string
  public $ResponseStatus; // ResponseStatus
  public $AutoArchive; // string
}

/*class ResponseStatus {
}*/

class RequestResponsesResponse {
  public $Responses; // Response
  public $Error; // string
}

class Response {
  public $ResponseId; // long
  public $OrigTraceNbr; // string
  public $EmedixTraceNumber; // string
  public $ResponseType; // string
  public $ResponseStatus; // string
  public $ResponseMsg; // string
  public $ResponseDate; // string
  public $ResponseDetails; // ResponseDetail
}

class ResponseDetail {
  public $ErrorCode; // string
  public $ErrorLoop; // string
  public $ErrorSeg; // string
  public $ErrorField; // string
  public $ErrorComponent; // string
  public $ErrorData; // string
  public $DetailMsg; // string
  public $SegLineNbr; // int
  public $ErrorSegIndex; // int
}

class FileUpload {
  public $User; // string
  public $Pass; // string
  public $Dsn; // string
  public $SourceId; // SourceId
  public $Attachment; // base64Binary
  public $FileType; // FileType
  public $FileFormatType; // FileFormatType
  public $FileName; // FileName
}

/*class SourceId {
}

class FileType {
}

class FileFormatType {
}

class FileName {
}*/

class FileRequest {
  public $User; // string
  public $Pass; // string
  public $Dsn; // string
  public $SourceId; // SourceId
  public $FileID; // string
}

/*class SourceId {
}*/

class DirectoryListRequest {
  public $User; // string
  public $Pass; // string
  public $Dsn; // string
  public $SourceId; // SourceId
  public $ResponseType; // ResponseType
}

/*class SourceId {
}

class ResponseType {
}*/

class EligibilityRequest {
  public $User; // string
  public $Pass; // string
  public $AcctId; // string
  public $Npi; // Npi
  public $ProvLastOrgName; // ProvLastOrgName
  public $ProvFirstName; // ProvFirstName
  public $PayerCode; // string
  public $InsId; // InsId
  public $InsSSN; // InsSSN
  public $InsFName; // InsFName
  public $InsLName; // InsLName
  public $InsMI; // InsMI
  public $InsSuffix; // InsSuffix
  public $InsDOB; // InsDOB
  public $Rel; // Rel
  public $DepFName; // DepFName
  public $DepLName; // DepLName
  public $DepMI; // DepMI
  public $DepSuffix; // DepSuffix
  public $DepDOB; // DepDOB
  public $Gender; // Gender
  public $ServType; // ServType
  public $Pos; // Pos
  public $FromDOS; // FromDOS
  public $ToDOS; // ToDOS
  public $ProcCode; // string
  public $DiagCode; // string
  public $ResponseType; // ResponseType
}

/*class Npi {
}

class ProvLastOrgName {
}

class ProvFirstName {
}

class InsId {
}

class InsSSN {
}

class InsFName {
}

class InsLName {
}

class InsMI {
}

class InsSuffix {
}

class InsDOB {
}

class Rel {
}

class DepFName {
}

class DepLName {
}

class DepMI {
}

class DepSuffix {
}

class DepDOB {
}

class Gender {
}

class ServType {
}

class Pos {
}

class FromDOS {
}

class ToDOS {
}

class ResponseType {
}*/

class DirectoryListResponses {
  public $Response; // DirectoryListResponse
  public $Error; // string
}

class DirectoryListResponse {
  public $FileID; // string
  public $FileName; // string
  public $FileType; // string
  public $FileDate; // string
}

class ANSIEligibilityRequest {
  public $User; // string
  public $Pass; // string
  public $AcctId; // string
  public $PayerCode; // string
  public $Attachment; // base64Binary
  public $ResponseType; // ResponseType
}

/*class ResponseType {
}*/

class FileResponse {
  public $FileID; // string
  public $FileName; // string
  public $FileContent; // base64Binary
  public $RespMessage; // string
}

class FileUploadStatement {
  public $User; // string
  public $Pass; // string
  public $Dsn; // string
  public $SourceId; // SourceId
  public $Attachment; // base64Binary
  public $FileType; // FileType
  public $FileFormatType; // FileFormatType
  public $FileName; // FileName
  public $SubmitterID; // SubmitterID
}

/*class SourceId {
}

class FileType {
}

class FileFormatType {
}

class FileName {
}

class SubmitterID {
}*/

class TransactionResponse {
  public $Status; // string
  public $Message; // string
  public $RetVal; // base64Binary
}

class DirectoryListStatementRequest {
  public $User; // string
  public $Pass; // string
  public $Dsn; // string
  public $SourceId; // SourceId
  public $ResponseType; // ResponseType
  public $SubmitterID; // string
}

/*class SourceId {
}

class ResponseType {
}*/

class sendEligRequest {
  public $req; // EligibilityRequest
}

class sendEligRequestResponse {
  public $return; // TransactionResponse
}

class requestFile {
  public $fileReq; // FileRequest
}

class requestFileResponse {
  public $return; // FileResponse
}

class archiveFile {
  public $fileReq; // FileRequest
}

class archiveFileResponse {
  public $return; // TransactionResponse
}

class sendFile {
  public $inputFile; // FileUpload
}

class sendFileResponse {
  public $return; // TransactionResponse
}

class sendEligRequestANSI {
  public $req; // ANSIEligibilityRequest
}

class sendEligRequestANSIResponse {
  public $return; // TransactionResponse
}

class sendClaimStatusRequestANSI {
  public $req; // ANSIEligibilityRequest
}

class sendClaimStatusRequestANSIResponse {
  public $return; // TransactionResponse
}

class requestResponses {
  public $responseReq; // ResponseRequest
}

/*class requestResponsesResponse {
  public $return; // RequestResponsesResponse
}*/

class archiveResponse {
  public $archiveResponseReq; // ArchiveResponseRequest
}

class archiveResponseResponse {
  public $return; // TransactionResponse
}

class sendFileStatement {
  public $inputFile; // FileUploadStatement
}

class sendFileStatementResponse {
  public $return; // TransactionResponse
}

class requestDirectoryListStatements {
  public $dirListReq; // DirectoryListStatementRequest
}

class requestDirectoryListStatementsResponse {
  public $return; // DirectoryListResponses
}

class requestDirectoryList {
  public $dirListReq; // DirectoryListRequest
}

class requestDirectoryListResponse {
  public $return; // DirectoryListResponses
}


/**
 * TransactionPortalService class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class TransactionPortalService extends SoapClient {

  private static $classmap = array(
                                    'ArchiveResponseRequest' => 'ArchiveResponseRequest',
                                    'ResponseRequest' => 'ResponseRequest',
                                    'ResponseStatus' => 'ResponseStatus',
                                    'RequestResponsesResponse' => 'RequestResponsesResponse',
                                    'Response' => 'Response',
                                    'ResponseDetail' => 'ResponseDetail',
                                    'FileUpload' => 'FileUpload',
                                    'SourceId' => 'SourceId',
                                    'FileType' => 'FileType',
                                    'FileFormatType' => 'FileFormatType',
                                    'FileName' => 'FileName',
                                    'FileRequest' => 'FileRequest',
                                    'SourceId' => 'SourceId',
                                    'DirectoryListRequest' => 'DirectoryListRequest',
                                    'SourceId' => 'SourceId',
                                    'ResponseType' => 'ResponseType',
                                    'EligibilityRequest' => 'EligibilityRequest',
                                    'Npi' => 'Npi',
                                    'ProvLastOrgName' => 'ProvLastOrgName',
                                    'ProvFirstName' => 'ProvFirstName',
                                    'InsId' => 'InsId',
                                    'InsSSN' => 'InsSSN',
                                    'InsFName' => 'InsFName',
                                    'InsLName' => 'InsLName',
                                    'InsMI' => 'InsMI',
                                    'InsSuffix' => 'InsSuffix',
                                    'InsDOB' => 'InsDOB',
                                    'Rel' => 'Rel',
                                    'DepFName' => 'DepFName',
                                    'DepLName' => 'DepLName',
                                    'DepMI' => 'DepMI',
                                    'DepSuffix' => 'DepSuffix',
                                    'DepDOB' => 'DepDOB',
                                    'Gender' => 'Gender',
                                    'ServType' => 'ServType',
                                    'Pos' => 'Pos',
                                    'FromDOS' => 'FromDOS',
                                    'ToDOS' => 'ToDOS',
                                    'ResponseType' => 'ResponseType',
                                    'DirectoryListResponses' => 'DirectoryListResponses',
                                    'DirectoryListResponse' => 'DirectoryListResponse',
                                    'ANSIEligibilityRequest' => 'ANSIEligibilityRequest',
                                    'ResponseType' => 'ResponseType',
                                    'FileResponse' => 'FileResponse',
                                    'FileUploadStatement' => 'FileUploadStatement',
                                    'SourceId' => 'SourceId',
                                    'FileType' => 'FileType',
                                    'FileFormatType' => 'FileFormatType',
                                    'FileName' => 'FileName',
                                    'SubmitterID' => 'SubmitterID',
                                    'TransactionResponse' => 'TransactionResponse',
                                    'DirectoryListStatementRequest' => 'DirectoryListStatementRequest',
                                    'SourceId' => 'SourceId',
                                    'ResponseType' => 'ResponseType',
                                    'sendEligRequest' => 'sendEligRequest',
                                    'sendEligRequestResponse' => 'sendEligRequestResponse',
                                    'requestFile' => 'requestFile',
                                    'requestFileResponse' => 'requestFileResponse',
                                    'archiveFile' => 'archiveFile',
                                    'archiveFileResponse' => 'archiveFileResponse',
                                    'sendFile' => 'sendFile',
                                    'sendFileResponse' => 'sendFileResponse',
                                    'sendEligRequestANSI' => 'sendEligRequestANSI',
                                    'sendEligRequestANSIResponse' => 'sendEligRequestANSIResponse',
                                    'sendClaimStatusRequestANSI' => 'sendClaimStatusRequestANSI',
                                    'sendClaimStatusRequestANSIResponse' => 'sendClaimStatusRequestANSIResponse',
                                    'requestResponses' => 'requestResponses',
                                    'requestResponsesResponse' => 'requestResponsesResponse',
                                    'archiveResponse' => 'archiveResponse',
                                    'archiveResponseResponse' => 'archiveResponseResponse',
                                    'sendFileStatement' => 'sendFileStatement',
                                    'sendFileStatementResponse' => 'sendFileStatementResponse',
                                    'requestDirectoryListStatements' => 'requestDirectoryListStatements',
                                    'requestDirectoryListStatementsResponse' => 'requestDirectoryListStatementsResponse',
                                    'requestDirectoryList' => 'requestDirectoryList',
                                    'requestDirectoryListResponse' => 'requestDirectoryListResponse',
                                   );

  public function TransactionPortalService($wsdl = "eMedix_WSDL.wsdl", $options = array()) {
   $options = array(
       'trace' => 1, 
       'location' => 'https://transportal.emedixus.com/TransactionPortal', 
       'exceptions' => 1,
       'stream_context' => stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false,))
       ),
       'ssl_method' => SOAP_SSL_METHOD_SSLv3,
       'uri' => 'https://TransactionPortal/TransactionPortalService',
       
   );
    foreach(self::$classmap as $key => $value) {
      if(!isset($options['classmap'][$key])) {
        $options['classmap'][$key] = $value;
      }
    }
    parent::__construct($wsdl, $options);
  }

  /**
   *  
   *
   * @param sendEligRequest $parameters
   * @return sendEligRequestResponse
   */
  public function sendEligRequest(sendEligRequest $parameters) {
    return $this->__soapCall('sendEligRequest', array($parameters),       array(
            'uri' => 'https://TransactionPortal/TransactionPortalService',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param requestFile $parameters
   * @return requestFileResponse
   */
  public function requestFile(requestFile $parameters) {
    return $this->__soapCall('requestFile', array($parameters),       array(
            'uri' => 'https://TransactionPortal/TransactionPortalService',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param archiveFile $parameters
   * @return archiveFileResponse
   */
  public function archiveFile(archiveFile $parameters) {
    return $this->__soapCall('archiveFile', array($parameters),       array(
            'uri' => 'https://TransactionPortal/TransactionPortalService',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param sendFile $parameters
   * @return sendFileResponse
   */
  public function sendFile(sendFile $parameters) {
    return $this->__soapCall('sendFile', array($parameters),       array(
            'uri' => 'https://TransactionPortal/TransactionPortalService',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param sendEligRequestANSI $parameters
   * @return sendEligRequestANSIResponse
   */
  public function sendEligRequestANSI(sendEligRequestANSI $parameters) {
    return $this->__soapCall('sendEligRequestANSI', array($parameters),       array(
            'uri' => 'https://TransactionPortal/TransactionPortalService',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param sendClaimStatusRequestANSI $parameters
   * @return sendClaimStatusRequestANSIResponse
   */
  public function sendClaimStatusRequestANSI(sendClaimStatusRequestANSI $parameters) {
    return $this->__soapCall('sendClaimStatusRequestANSI', array($parameters),       array(
            'uri' => 'https://TransactionPortal/TransactionPortalService',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param sendFileStatement $parameters
   * @return sendFileStatementResponse
   */
  public function sendFileStatement(sendFileStatement $parameters) {
    return $this->__soapCall('sendFileStatement', array($parameters),       array(
            'uri' => 'https://TransactionPortal/TransactionPortalService',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param requestDirectoryListStatements $parameters
   * @return requestDirectoryListStatementsResponse
   */
  public function requestDirectoryListStatements(requestDirectoryListStatements $parameters) {
    return $this->__soapCall('requestDirectoryListStatements', array($parameters),       array(
            'uri' => 'https://TransactionPortal/TransactionPortalService',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param requestDirectoryList $parameters
   * @return requestDirectoryListResponse
   */
  public function requestDirectoryList(requestDirectoryList $parameters) {
    return $this->__soapCall('requestDirectoryList', array($parameters),       array(
            'uri' => 'https://TransactionPortal/TransactionPortalService',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param requestResponses $parameters
   * @return requestResponsesResponse
   */
  public function requestResponses(requestResponses $parameters) {
    return $this->__soapCall('requestResponses', array($parameters),       array(
            'uri' => 'https://TransactionPortal/TransactionPortalService',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param archiveResponse $parameters
   * @return archiveResponseResponse
   */
  public function archiveResponse(archiveResponse $parameters) {
    return $this->__soapCall('archiveResponse', array($parameters),       array(
            'uri' => 'https://TransactionPortal/TransactionPortalService',
            'soapaction' => ''
           )
      );
  }

}

?>
