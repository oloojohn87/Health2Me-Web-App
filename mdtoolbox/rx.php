<?php
class WebServiceTest {
  public $AccountObj; // Account
}

class Account {
  public $AccountId; // int
  public $AccountAuthKey; // string
  public $PracticeId; // string
  public $PracticeName; // string
  public $UserId; // string
  public $UserName; // string
  public $UserPermissionLevel; // int
  public $UserFirstName; // string
  public $UserLastName; // string
  public $UserPosition; // string
}

class WebServiceTestResponse {
  public $WebServiceTestResult; // string
}

class GetDrugListDS {
  public $SearchName; // string
  public $BeginsContains; // SearchLike
  public $SearchFlag; // SearchType
  public $NameFlag; // NameType
  public $RxFlag; // RXType
  public $SearchNameFields; // SearchName
  public $PrescriberId; // int
  public $ActiveOnly; // boolean
  public $OptionalPbmId; // string
  public $OptionalFormularyId; // string
  public $OptionalCoverageListId; // string
  public $AccountObj; // Account
}

class SearchLike {
  const BeginsWith = 'BeginsWith';
  const Contains = 'Contains';
}

class SearchType {
  const Name = 'Name';
  const Common = 'Common';
  const _Class = 'Class';
  const Diagnosis = 'Diagnosis';
  const Favs = 'Favs';
  const Supplies = 'Supplies';
}

class NameType {
  const Brand = 'Brand';
  const Generic = 'Generic';
  const Both = 'Both';
}

class RXType {
  const RX = 'RX';
  const OTC = 'OTC';
  const Both = 'Both';
}

class SearchName {
  const DrugDisplayName = 'DrugDisplayName';
  const GenericName = 'GenericName';
  const Both = 'Both';
}

class GetDrugListDSResponse {
  public $GetDrugListDSResult; // GetDrugListDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugListDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class Status {
  const Error = 'Error';
  const Success = 'Success';
}

class GetDrugListWithStateDeaCodesDS {
  public $SearchName; // string
  public $BeginsContains; // SearchLike
  public $SearchFlag; // SearchType
  public $NameFlag; // NameType
  public $RxFlag; // RXType
  public $SearchNameFields; // SearchName
  public $PrescriberId; // string
  public $Prescriber2LetterState; // string
  public $ActiveOnly; // boolean
  public $OptionalPbmId; // string
  public $OptionalFormularyId; // string
  public $OptionalCoverageListId; // string
  public $AccountObj; // Account
}

class GetDrugListWithStateDeaCodesDSResponse {
  public $GetDrugListWithStateDeaCodesDSResult; // GetDrugListWithStateDeaCodesDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugListWithStateDeaCodesDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugListwStateDeaPlusVoucherDS {
  public $SearchName; // string
  public $BeginsContains; // SearchLike
  public $SearchFlag; // SearchType
  public $NameFlag; // NameType
  public $RxFlag; // RXType
  public $SearchNameFields; // SearchName
  public $PrescriberId; // string
  public $Prescriber2LetterState; // string
  public $ActiveOnly; // boolean
  public $OptionalPbmId; // string
  public $OptionalFormularyId; // string
  public $OptionalCoverageListId; // string
  public $PatientId; // string
  public $VoucherAuth; // string
  public $VouchersOnly; // boolean
  public $AccountObj; // Account
}

class GetDrugListwStateDeaPlusVoucherDSResponse {
  public $GetDrugListwStateDeaPlusVoucherDSResult; // GetDrugListwStateDeaPlusVoucherDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugListwStateDeaPlusVoucherDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugListWithPagingDS {
  public $SearchName; // string
  public $BeginsContains; // SearchLike
  public $NameFlag; // NameType
  public $RxFlag; // RXType
  public $SearchNameFields; // SearchName
  public $PrescriberId; // int
  public $ActiveOnly; // boolean
  public $PageSize; // int
  public $ReturnPageNumber; // int
  public $AccountObj; // Account
}

class GetDrugListWithPagingDSResponse {
  public $GetDrugListWithPagingDSResult; // GetDrugListWithPagingDSResult
  public $TotalRecordsAvailable; // int
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugListWithPagingDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetFormularyDetailDisplayHTML {
  public $UnqDrugId; // int
  public $PbmId; // string
  public $CopayId; // string
  public $FormularyId; // string
  public $CoverId; // string
  public $AltId; // string
  public $AccountObj; // Account
}

class GetFormularyDetailDisplayHTMLResponse {
  public $GetFormularyDetailDisplayHTMLResult; // string
  public $ReportingDisplayFlags; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetFormularyDetailDisplaySupplyHTML {
  public $SupplyNDC; // string
  public $PbmId; // string
  public $CopayId; // string
  public $FormularyId; // string
  public $CoverId; // string
  public $AltId; // string
  public $AccountObj; // Account
}

class GetFormularyDetailDisplaySupplyHTMLResponse {
  public $GetFormularyDetailDisplaySupplyHTMLResult; // string
  public $ReportingDisplayFlags; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetFormularyCoverageInfoDS {
  public $UnqDrugId; // int
  public $NDC; // string
  public $PbmId; // string
  public $CoverageId; // string
  public $AccountObj; // Account
}

class GetFormularyCoverageInfoDSResponse {
  public $GetFormularyCoverageInfoDSResult; // GetFormularyCoverageInfoDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetFormularyCoverageInfoDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetFormularyCopayInfoDS {
  public $UnqDrugId; // int
  public $NDC; // string
  public $PbmId; // string
  public $CopayId; // string
  public $FormularyCoverage; // string
  public $AccountObj; // Account
}

class GetFormularyCopayInfoDSResponse {
  public $GetFormularyCopayInfoDSResult; // GetFormularyCopayInfoDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetFormularyCopayInfoDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetFormularyDrugAltsDS {
  public $UnqDrugId; // int
  public $SupplyNDC; // string
  public $PbmId; // string
  public $AlternateId; // string
  public $FormularyId; // string
  public $CoverageId; // string
  public $CopayId; // string
  public $AccountObj; // Account
}

class GetFormularyDrugAltsDSResponse {
  public $GetFormularyDrugAltsDSResult; // GetFormularyDrugAltsDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetFormularyDrugAltsDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugAltsDS {
  public $UnqDrugId; // int
  public $SupplyNDC; // string
  public $PbmId; // string
  public $FormularyId; // string
  public $CoverageId; // string
  public $CopayId; // string
  public $AccountObj; // Account
}

class GetDrugAltsDSResponse {
  public $GetDrugAltsDSResult; // GetDrugAltsDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugAltsDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugAltsPerDrugClassDS {
  public $UnqDrugId; // int
  public $PbmId; // string
  public $FormularyId; // string
  public $CoverageId; // string
  public $CopayId; // string
  public $AccountObj; // Account
}

class GetDrugAltsPerDrugClassDSResponse {
  public $GetDrugAltsPerDrugClassDSResult; // GetDrugAltsPerDrugClassDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugAltsPerDrugClassDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugClassesDS {
  public $GenDrugId; // string
  public $AccountObj; // Account
}

class GetDrugClassesDSResponse {
  public $GetDrugClassesDSResult; // GetDrugClassesDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugClassesDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugConditionsDS {
  public $DrugProductId; // string
  public $AccountObj; // Account
}

class GetDrugConditionsDSResponse {
  public $GetDrugConditionsDSResult; // GetDrugConditionsDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugConditionsDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugWarningsDS {
  public $DrugId; // string
  public $AccountObj; // Account
}

class GetDrugWarningsDSResponse {
  public $GetDrugWarningsDSResult; // GetDrugWarningsDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugWarningsDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugWarnPregLactDS {
  public $DrugId; // string
  public $AccountObj; // Account
}

class GetDrugWarnPregLactDSResponse {
  public $GetDrugWarnPregLactDSResult; // GetDrugWarnPregLactDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugWarnPregLactDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugWarningLabelsDS {
  public $DrugProductId; // string
  public $AccountObj; // Account
}

class GetDrugWarningLabelsDSResponse {
  public $GetDrugWarningLabelsDSResult; // GetDrugWarningLabelsDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugWarningLabelsDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugPatLeafletURLByProduct {
  public $DrugProductId; // string
  public $Lang; // Language
  public $AccountObj; // Account
}

class Language {
  const English = 'English';
  const Spanish = 'Spanish';
  const French = 'French';
  const EnglishPed = 'EnglishPed';
  const SpanishPed = 'SpanishPed';
  const FrenchPed = 'FrenchPed';
}

class GetDrugPatLeafletURLByProductResponse {
  public $GetDrugPatLeafletURLByProductResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugPatLeafletURL {
  public $DrugId; // int
  public $Lang; // Language
  public $AccountObj; // Account
}

class GetDrugPatLeafletURLResponse {
  public $GetDrugPatLeafletURLResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugPatLeafletURLByGeneric {
  public $DrugId; // string
  public $Lang; // Language
  public $AccountObj; // Account
}

class GetDrugPatLeafletURLByGenericResponse {
  public $GetDrugPatLeafletURLByGenericResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugDosingRefDS {
  public $DrugGenId; // string
  public $AccountObj; // Account
}

class GetDrugDosingRefDSResponse {
  public $GetDrugDosingRefDSResult; // GetDrugDosingRefDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugDosingRefDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugAllergyInterDS {
  public $DrugId; // int
  public $AccountObj; // Account
}

class GetDrugAllergyInterDSResponse {
  public $GetDrugAllergyInterDSResult; // GetDrugAllergyInterDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugAllergyInterDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugDosingDataDS {
  public $DrugId; // int
  public $AccountObj; // Account
}

class GetDrugDosingDataDSResponse {
  public $GetDrugDosingDataDSResult; // GetDrugDosingDataDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugDosingDataDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugDosingDataPerDS {
  public $DrugId; // int
  public $PatientAgeInDays; // int
  public $PatientWeightinKG; // int
  public $PatientGenderCode; // string
  public $OptionalConditionId; // int
  public $AccountObj; // Account
}

class GetDrugDosingDataPerDSResponse {
  public $GetDrugDosingDataPerDSResult; // GetDrugDosingDataPerDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugDosingDataPerDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugConditionInterDS {
  public $DrugId; // int
  public $AccountObj; // Account
}

class GetDrugConditionInterDSResponse {
  public $GetDrugConditionInterDSResult; // GetDrugConditionInterDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugConditionInterDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugDrugInterDS {
  public $DrugId; // int
  public $AccountObj; // Account
}

class GetDrugDrugInterDSResponse {
  public $GetDrugDrugInterDSResult; // GetDrugDrugInterDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugDrugInterDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugFoodInterDS {
  public $GenDrugId; // string
  public $AccountObj; // Account
}

class GetDrugFoodInterDSResponse {
  public $GetDrugFoodInterDSResult; // GetDrugFoodInterDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugFoodInterDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugDupTherClassDS {
  public $DrugId; // int
  public $AccountObj; // Account
}

class GetDrugDupTherClassDSResponse {
  public $GetDrugDupTherClassDSResult; // GetDrugDupTherClassDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugDupTherClassDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugSideEffectsDS {
  public $DrugId; // string
  public $AccountObj; // Account
}

class GetDrugSideEffectsDSResponse {
  public $GetDrugSideEffectsDSResult; // GetDrugSideEffectsDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugSideEffectsDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugIdListbyDupTherClassDS {
  public $DupTherClassId; // int
  public $AccountObj; // Account
}

class GetDrugIdListbyDupTherClassDSResponse {
  public $GetDrugIdListbyDupTherClassDSResult; // GetDrugIdListbyDupTherClassDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugIdListbyDupTherClassDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugGenListbyDupTherClassDS {
  public $DupTherClassId; // int
  public $AccountObj; // Account
}

class GetDrugGenListbyDupTherClassDSResponse {
  public $GetDrugGenListbyDupTherClassDSResult; // GetDrugGenListbyDupTherClassDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugGenListbyDupTherClassDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetVaccineNDCPackagingDS {
  public $SearchName; // string
  public $OptionalMVX; // string
  public $AccountObj; // Account
}

class GetVaccineNDCPackagingDSResponse {
  public $GetVaccineNDCPackagingDSResult; // GetVaccineNDCPackagingDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetVaccineNDCPackagingDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugNDCPackagingDS {
  public $DrugProductId; // string
  public $AccountObj; // Account
}

class GetDrugNDCPackagingDSResponse {
  public $GetDrugNDCPackagingDSResult; // GetDrugNDCPackagingDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugNDCPackagingDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugNDCPackagingSummaryDS {
  public $DrugProductId; // string
  public $AccountObj; // Account
}

class GetDrugNDCPackagingSummaryDSResponse {
  public $GetDrugNDCPackagingSummaryDSResult; // GetDrugNDCPackagingSummaryDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugNDCPackagingSummaryDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugNDCPackagingSummaryByUnqIdDS {
  public $DrugUnqId; // int
  public $AccountObj; // Account
}

class GetDrugNDCPackagingSummaryByUnqIdDSResponse {
  public $GetDrugNDCPackagingSummaryByUnqIdDSResult; // GetDrugNDCPackagingSummaryByUnqIdDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugNDCPackagingSummaryByUnqIdDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugDS {
  public $DrugId; // string
  public $DrugProductId; // int
  public $UnqProductId; // int
  public $RxNorm_CUI; // string
  public $ActiveOnly; // boolean
  public $AccountObj; // Account
}

class GetDrugDSResponse {
  public $GetDrugDSResult; // GetDrugDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugInfoCSADS {
  public $DrugId; // string
  public $DrugProductId; // int
  public $UnqProductId; // int
  public $ActiveOnly; // boolean
  public $Prescriber2LetterState; // string
  public $Pharmacy2LetterState; // string
  public $AccountObj; // Account
}

class GetDrugInfoCSADSResponse {
  public $GetDrugInfoCSADSResult; // GetDrugInfoCSADSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugInfoCSADSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugByNDCDS {
  public $NDC11Code; // string
  public $ActiveOnly; // boolean
  public $AccountObj; // Account
}

class GetDrugByNDCDSResponse {
  public $GetDrugByNDCDSResult; // GetDrugByNDCDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugByNDCDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugAndFormularyDS {
  public $DrugId; // string
  public $DrugProductId; // int
  public $UnqProductId; // int
  public $RxNorm_CUI; // string
  public $ActiveOnly; // boolean
  public $OptionalPbmId; // string
  public $OptionalFormularyId; // string
  public $OptionalCoverageListId; // string
  public $AccountObj; // Account
}

class GetDrugAndFormularyDSResponse {
  public $GetDrugAndFormularyDSResult; // GetDrugAndFormularyDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugAndFormularyDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugAndFormularySupplyDS {
  public $NDC11; // string
  public $OptionalPbmId; // string
  public $OptionalFormularyId; // string
  public $OptionalCoverageListId; // string
  public $AccountObj; // Account
}

class GetDrugAndFormularySupplyDSResponse {
  public $GetDrugAndFormularySupplyDSResult; // GetDrugAndFormularySupplyDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugAndFormularySupplyDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetFrequencyDS {
  public $SearchName; // string
  public $AccountObj; // Account
}

class GetFrequencyDSResponse {
  public $GetFrequencyDSResult; // GetFrequencyDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetFrequencyDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDirectionsDS {
  public $SearchName; // string
  public $uom; // string
  public $AccountObj; // Account
}

class GetDirectionsDSResponse {
  public $GetDirectionsDSResult; // GetDirectionsDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDirectionsDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetDrugQtyUofMDS {
  public $AccountObj; // Account
}

class GetDrugQtyUofMDSResponse {
  public $GetDrugQtyUofMDSResult; // GetDrugQtyUofMDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetDrugQtyUofMDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class ConvertDrugIds {
  public $DrugIdsToConvert; // ArrayOfString
  public $DrugIdSource; // string
  public $AccountObj; // Account
}

class ConvertDrugIdsResponse {
  public $ConvertDrugIdsResult; // ConvertDrugIdsResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class ConvertDrugIdsResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetAlertsAllDS {
  public $DrugId; // int
  public $AllergyIdArray; // ArrayOfInt
  public $CurMedGenericIdArray; // ArrayOfString
  public $ConditionIdArray; // ArrayOfInt
  public $ICD9CodeArray; // ArrayOfString
  public $IncludeDrugAllergy; // boolean
  public $IncludeDrugDrug; // boolean
  public $IncludeDrugCondition; // boolean
  public $IncludeDupTherapy; // boolean
  public $AccountObj; // Account
}

class GetAlertsAllDSResponse {
  public $GetAlertsAllDSResult; // GetAlertsAllDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetAlertsAllDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetPharmacyDS {
  public $PharmacyObj; // Pharmacy
  public $ActiveOnly; // boolean
  public $IncludeRetail; // boolean
  public $IncludeMailOrder; // boolean
  public $IncludeFax; // boolean
  public $IncludeSpecialty; // boolean
  public $IncludeLongTerm; // boolean
  public $IncludeTwentyFourHour; // boolean
  public $AccountObj; // Account
}

class Pharmacy {
  public $NCPDPID; // string
  public $StoreName; // string
  public $Addr1; // string
  public $Addr2; // string
  public $City; // string
  public $State; // string
  public $Zip; // string
  public $Phone; // string
  public $Fax; // string
  public $Email; // string
  public $ServiceLevel; // int
}

class GetPharmacyDSResponse {
  public $GetPharmacyDSResult; // GetPharmacyDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetPharmacyDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetPharmacyXML {
  public $PharmacyObj; // Pharmacy
  public $ActiveOnly; // boolean
  public $IncludeRetail; // boolean
  public $IncludeMailOrder; // boolean
  public $IncludeFax; // boolean
  public $IncludeSpecialty; // boolean
  public $IncludeLongTerm; // boolean
  public $IncludeTwentyFourHour; // boolean
  public $AccountObj; // Account
}

class GetPharmacyXMLResponse {
  public $GetPharmacyXMLResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetPharmacyObj {
  public $PharmacyObj; // Pharmacy
  public $ActiveOnly; // boolean
  public $IncludeRetail; // boolean
  public $IncludeMailOrder; // boolean
  public $IncludeFax; // boolean
  public $IncludeSpecialty; // boolean
  public $IncludeLongTerm; // boolean
  public $IncludeTwentyFourHour; // boolean
  public $AccountObj; // Account
}

class GetPharmacyObjResponse {
  public $GetPharmacyObjResult; // ArrayOfPharmacy
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetAllergyDS {
  public $SearchName; // string
  public $AccountObj; // Account
}

class GetAllergyDSResponse {
  public $GetAllergyDSResult; // GetAllergyDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetAllergyDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetAllergyXML {
  public $SearchName; // string
  public $AccountObj; // Account
}

class GetAllergyXMLResponse {
  public $GetAllergyXMLResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetConditionDS {
  public $SearchName; // string
  public $AccountObj; // Account
}

class GetConditionDSResponse {
  public $GetConditionDSResult; // GetConditionDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetConditionDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetRefillRequestsDS {
  public $PrescriberId; // ArrayOfString
  public $AccountObj; // Account
}

class GetRefillRequestsDSResponse {
  public $GetRefillRequestsDSResult; // GetRefillRequestsDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetRefillRequestsDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetRefillRequestsAllDS {
  public $PrescriberId; // ArrayOfString
  public $ReqStatus; // RXRefillReqStatus
  public $StartDate; // dateTime
  public $EndDate; // dateTime
  public $AccountObj; // Account
}

class RXRefillReqStatus {
  const NewNotRespondedTo = 'NewNotRespondedTo';
  const SentResponse = 'SentResponse';
  const SentResponseVerified = 'SentResponseVerified';
  const SentResponseError = 'SentResponseError';
}

class GetRefillRequestsAllDSResponse {
  public $GetRefillRequestsAllDSResult; // GetRefillRequestsAllDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetRefillRequestsAllDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetRefillRequestsAllObj {
  public $PrescriberId; // ArrayOfString
  public $ReqStatus; // RXRefillReqStatus
  public $StartDate; // dateTime
  public $EndDate; // dateTime
  public $AccountObj; // Account
}

class GetRefillRequestsAllObjResponse {
  public $GetRefillRequestsAllObjResult; // ArrayOfRefillRequestRecord
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class RefillRequestRecord {
  public $RefillReqId; // int
  public $PatientLastReq; // string
  public $PatientFirstReq; // string
  public $Drug; // string
  public $Directions; // string
  public $RefillRequestDate; // dateTime
  public $PharmacyNCPDP; // string
  public $Pharmacy; // string
  public $ProviderId; // string
  public $ProviderName; // string
  public $RefillResponseDate; // dateTime
  public $PatientId; // string
  public $PatientLast; // string
  public $PatientFirst; // string
  public $RefillResponse; // string
  public $NumRefillsApproved; // int
  public $RefillResponseNote; // string
  public $RefilledRxId; // int
  public $NewRxId; // int
}

class GetRefillRequestsCount {
  public $PrescriberId; // ArrayOfString
  public $AccountObj; // Account
}

class GetRefillRequestsCountResponse {
  public $GetRefillRequestsCountResult; // int
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetRefillRequestDetailDS {
  public $RefillRequestId; // int
  public $AccountObj; // Account
}

class GetRefillRequestDetailDSResponse {
  public $GetRefillRequestDetailDSResult; // GetRefillRequestDetailDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetRefillRequestDetailDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetRefillResponseDenialCodesDS {
  public $AccountObj; // Account
}

class GetRefillResponseDenialCodesDSResponse {
  public $GetRefillResponseDenialCodesDSResult; // GetRefillResponseDenialCodesDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetRefillResponseDenialCodesDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetPatientEligibilityDS {
  public $PatientObj; // Patient
  public $PrescriberObj; // Prescriber
  public $DateOfService; // dateTime
  public $AccountObj; // Account
}

class Patient {
  public $ID; // string
  public $MRN; // string
  public $LastName; // string
  public $FirstName; // string
  public $MiddleName; // string
  public $Suffix; // string
  public $Prefix; // string
  public $Gender; // string
  public $SSN; // string
  public $DOB; // dateTime
  public $Addr1; // string
  public $Addr2; // string
  public $City; // string
  public $State; // string
  public $Zip; // string
  public $HomePh; // string
  public $WorkPh; // string
  public $CellPh; // string
  public $Email; // string
  public $PatientEligID; // string
  public $PatientPharmacyNCPDPID; // string
  public $PatientPharmacy2NCPDPID; // string
}

class Prescriber {
  public $ID; // string
  public $SPI; // string
  public $LastName; // string
  public $FirstName; // string
  public $MiddleName; // string
  public $Suffix; // string
  public $Prefix; // string
  public $NPI; // string
  public $DEA; // string
  public $LocationID; // string
  public $ClinicName; // string
  public $Addr1; // string
  public $Addr2; // string
  public $City; // string
  public $State; // string
  public $Zip; // string
  public $Phone; // string
  public $Fax; // string
  public $Email; // string
  public $Current; // boolean
  public $UserId; // string
  public $UserName; // string
  public $StateLicenseNumber; // string
}

class GetPatientEligibilityDSResponse {
  public $GetPatientEligibilityDSResult; // GetPatientEligibilityDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetPatientEligibilityDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetPatientEligibilityXML {
  public $PatientObj; // Patient
  public $PrescriberObj; // Prescriber
  public $DateOfService; // dateTime
  public $AccountObj; // Account
}

class GetPatientEligibilityXMLResponse {
  public $GetPatientEligibilityXMLResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetPatientEligibilityOnFileDS {
  public $PatientObj; // Patient
  public $AccountObj; // Account
}

class GetPatientEligibilityOnFileDSResponse {
  public $GetPatientEligibilityOnFileDSResult; // GetPatientEligibilityOnFileDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetPatientEligibilityOnFileDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetPatientEligibilityOnFileXML {
  public $PatientObj; // Patient
  public $AccountObj; // Account
}

class GetPatientEligibilityOnFileXMLResponse {
  public $GetPatientEligibilityOnFileXMLResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetPatientRxHistoryRequestDS {
  public $PatientObj; // Patient
  public $PrescriberObj; // Prescriber
  public $Consent; // string
  public $BeginDate; // dateTime
  public $ThruDate; // dateTime
  public $WhichElig; // int
  public $AccountObj; // Account
}

class GetPatientRxHistoryRequestDSResponse {
  public $GetPatientRxHistoryRequestDSResult; // GetPatientRxHistoryRequestDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetPatientRxHistoryRequestDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class CheckPrescriberRegistration {
  public $UniqueSPI; // string
  public $AccountObj; // Account
}

class CheckPrescriberRegistrationResponse {
  public $CheckPrescriberRegistrationResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class CheckPrescriberRegistrationPerId {
  public $PrescriberId; // string
  public $LocationId; // string
  public $AccountObj; // Account
}

class CheckPrescriberRegistrationPerIdResponse {
  public $CheckPrescriberRegistrationPerIdResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class RegisterPrescriber {
  public $PrescriberObj; // Prescriber
  public $RegisterFor; // SurescriptsServices
  public $AccountObj; // Account
}

class SurescriptsServices {
  public $NewRx; // boolean
  public $RefillReq; // boolean
  public $EPCS; // boolean
  public $Cancel; // boolean
  public $Fill; // boolean
}

class RegisterPrescriberResponse {
  public $RegisterPrescriberResult; // boolean
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class eSendRefillReqResponse {
  public $RefillRequestId; // int
  public $RefResp; // RXRefillResponse
  public $OptRefNote; // string
  public $NumRefillsAppr; // int
  public $DenyReasonCode; // string
  public $AccountObj; // Account
}

class RXRefillResponse {
  const NotAnswered = 'NotAnswered';
  const Approved = 'Approved';
  const Denied = 'Denied';
  const DeniedNewRxToFollow = 'DeniedNewRxToFollow';
}

class eSendRefillReqResponseResponse {
  public $eSendRefillReqResponseResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetNewMessageAlertsCount {
  public $AccountObj; // Account
}

class GetNewMessageAlertsCountResponse {
  public $GetNewMessageAlertsCountResult; // int
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetNewMessageAlerts {
  public $AccountObj; // Account
}

class GetNewMessageAlertsResponse {
  public $GetNewMessageAlertsResult; // GetNewMessageAlertsResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetNewMessageAlertsResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class UpdateErrorMessageAlertComplete {
  public $AlertId; // string
  public $AccountObj; // Account
}

class UpdateErrorMessageAlertCompleteResponse {
  public $UpdateErrorMessageAlertCompleteResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetErrorMessageAlertsDS {
  public $ProviderId; // string
  public $AccountObj; // Account
}

class GetErrorMessageAlertsDSResponse {
  public $GetErrorMessageAlertsDSResult; // GetErrorMessageAlertsDSResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetErrorMessageAlertsDSResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetErrorMessageAlertsCount {
  public $ProviderId; // string
  public $AccountObj; // Account
}

class GetErrorMessageAlertsCountResponse {
  public $GetErrorMessageAlertsCountResult; // int
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GeteSendMessageStatus {
  public $MessageId; // string
  public $AccountObj; // Account
}

class GeteSendMessageStatusResponse {
  public $GeteSendMessageStatusResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GeteSendRefillMessageStatus {
  public $MessageId; // string
  public $AccountObj; // Account
}

class GeteSendRefillMessageStatusResponse {
  public $GeteSendRefillMessageStatusResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class eSendNewRx {
  public $PrescriberObj; // Prescriber
  public $PatientObj; // Patient
  public $PrescriptionObj; // Prescription
  public $AutoRegisterPrescriber; // boolean
  public $AccountObj; // Account
}

class Prescription {
  public $PrescriptionID; // string
  public $RxRefillNumber; // string
  public $PharmacyNCPDPID; // string
  public $DrugID; // string
  public $Supply; // boolean
  public $Compound; // boolean
  public $SupplyOrCompoundName; // string
  public $Directions; // string
  public $Qty; // decimal
  public $QtyQual; // string
  public $Refills; // int
  public $RefillQual; // string
  public $DaysSupply; // int
  public $SubstitutionAllowedFlag; // string
  public $PharmacyNote; // string
  public $Schedule; // int
  public $FormularyDisplayFlags; // string
  public $FormularyStatus; // string
}

class eSendNewRxResponse {
  public $eSendNewRxResult; // string
  public $RouteStatusFlag; // RXRouteStatus
  public $WentByFax; // boolean
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class RXRouteStatus {
  const NotRouted = 'NotRouted';
  const Sent = 'Sent';
  const SentVerified = 'SentVerified';
  const Failed = 'Failed';
}

class UpdatePrinted {
  public $PrescriberObj; // Prescriber
  public $PatientObj; // Patient
  public $PrescriptionObj; // Prescription
  public $AccountObj; // Account
}

class UpdatePrintedResponse {
  public $UpdatePrintedResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class UpdateDataForScreens {
  public $Prescribers; // ArrayOfPrescriber
  public $Locations; // ArrayOfLocation
  public $PatientObj; // Patient
  public $PatientMedications; // ArrayOfMedicationRecord
  public $PatientAllergies; // ArrayOfAllergyRecord
  public $PatientConditions; // ArrayOfConditionRecord
  public $PatientCurrentVitals; // VitalRecord
  public $CheckPatEligibility; // boolean
  public $EligCheckEncounterDate; // dateTime
  public $EligCheckPrescriber; // Prescriber
  public $AccountObj; // Account
}

class Location {
  public $ID; // string
  public $ClinicName; // string
  public $Addr1; // string
  public $Addr2; // string
  public $City; // string
  public $State; // string
  public $Zip; // string
  public $Phone; // string
  public $Fax; // string
  public $Current; // boolean
}

class MedicationRecord {
  public $ID; // string
  public $DrugID; // string
  public $NDC; // string
  public $RxNormCode; // string
  public $Drug; // string
  public $Supply; // boolean
  public $Compound; // boolean
  public $Directions; // string
  public $Qty; // double
  public $QtyQual; // string
  public $Refills; // int
  public $DaysSupply; // int
  public $SubstitutionAllowedFlag; // int
  public $Schedule; // int
  public $PharmacyNote; // string
  public $InternalNote; // string
  public $PrescriberId; // string
  public $LocationId; // string
  public $PrescriptionDate; // dateTime
  public $SignDate; // dateTime
  public $Status; // RXStatus
  public $PharmacyNcpdpId; // string
  public $RouteDetail; // string
  public $PartnerMedID; // string
}

class RXStatus {
  const Pending = 'Pending';
  const Ordered = 'Ordered';
  const Current = 'Current';
  const Historical = 'Historical';
  const Discontinued = 'Discontinued';
  const Deleted = 'Deleted';
}

class AllergyRecord {
  public $AllergyName; // string
  public $AllergyId; // int
  public $SeverityLevel; // int
  public $Reaction; // string
}

class ConditionRecord {
  public $ConditionName; // string
  public $ConditionId; // int
  public $ICD9Code; // string
  public $ConditionDate; // dateTime
}

class VitalRecord {
  public $VitalDate; // dateTime
  public $Weight; // float
  public $Height; // float
  public $BMI; // float
  public $BPSys; // int
  public $BPDia; // int
  public $Temp; // float
  public $Pulse; // float
  public $Notes; // string
}

class UpdateDataForScreensResponse {
  public $UpdateDataForScreensResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetPrescriptionsForPatient {
  public $PatientId; // string
  public $AccountObj; // Account
}

class GetPrescriptionsForPatientResponse {
  public $GetPrescriptionsForPatientResult; // GetPrescriptionsForPatientResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetPrescriptionsForPatientResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetPrescriptionsForPatientXML {
  public $PatientId; // string
  public $AccountObj; // Account
}

class GetPrescriptionsForPatientXMLResponse {
  public $GetPrescriptionsForPatientXMLResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetPrescriptionsForPatientObj {
  public $PatientId; // string
  public $AccountObj; // Account
}

class GetPrescriptionsForPatientObjResponse {
  public $GetPrescriptionsForPatientObjResult; // ArrayOfMedicationRecord
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetAllergiesForPatient {
  public $PatientId; // string
  public $AccountObj; // Account
}

class GetAllergiesForPatientResponse {
  public $GetAllergiesForPatientResult; // GetAllergiesForPatientResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetAllergiesForPatientResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class GetAllergiesForPatientObj {
  public $PatientId; // string
  public $AccountObj; // Account
}

class GetAllergiesForPatientObjResponse {
  public $GetAllergiesForPatientObjResult; // ArrayOfAllergyRecord
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetPrescriberAllowedSuffixStr {
  public $AccountObj; // Account
}

class GetPrescriberAllowedSuffixStrResponse {
  public $GetPrescriberAllowedSuffixStrResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetPrescriptionsByPrescriber {
  public $PrescriberId; // string
  public $StartDate; // dateTime
  public $EndDate; // dateTime
  public $AccountObj; // Account
}

class GetPrescriptionsByPrescriberResponse {
  public $GetPrescriptionsByPrescriberResult; // GetPrescriptionsByPrescriberResult
  public $StatusFlag; // Status
  public $StatusMsg; // string
}

class GetPrescriptionsByPrescriberResult {
  public $schema; // <anyXML>
  public $any; // <anyXML>
}

class UpdateDataPatientVitals {
  public $PatientId; // string
  public $PatientVitals; // ArrayOfVitalRecord
  public $AccountObj; // Account
}

class UpdateDataPatientVitalsResponse {
  public $UpdateDataPatientVitalsResult; // string
  public $StatusFlag; // Status
  public $StatusMsg; // string
}


/**
 * rx class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class rx extends SoapClient {

  private static $classmap = array(
                                    'WebServiceTest' => 'WebServiceTest',
                                    'Account' => 'Account',
                                    'WebServiceTestResponse' => 'WebServiceTestResponse',
                                    'GetDrugListDS' => 'GetDrugListDS',
                                    'SearchLike' => 'SearchLike',
                                    'SearchType' => 'SearchType',
                                    'NameType' => 'NameType',
                                    'RXType' => 'RXType',
                                    'SearchName' => 'SearchName',
                                    'GetDrugListDSResponse' => 'GetDrugListDSResponse',
                                    'GetDrugListDSResult' => 'GetDrugListDSResult',
                                    'Status' => 'Status',
                                    'GetDrugListWithStateDeaCodesDS' => 'GetDrugListWithStateDeaCodesDS',
                                    'GetDrugListWithStateDeaCodesDSResponse' => 'GetDrugListWithStateDeaCodesDSResponse',
                                    'GetDrugListWithStateDeaCodesDSResult' => 'GetDrugListWithStateDeaCodesDSResult',
                                    'GetDrugListwStateDeaPlusVoucherDS' => 'GetDrugListwStateDeaPlusVoucherDS',
                                    'GetDrugListwStateDeaPlusVoucherDSResponse' => 'GetDrugListwStateDeaPlusVoucherDSResponse',
                                    'GetDrugListwStateDeaPlusVoucherDSResult' => 'GetDrugListwStateDeaPlusVoucherDSResult',
                                    'GetDrugListWithPagingDS' => 'GetDrugListWithPagingDS',
                                    'GetDrugListWithPagingDSResponse' => 'GetDrugListWithPagingDSResponse',
                                    'GetDrugListWithPagingDSResult' => 'GetDrugListWithPagingDSResult',
                                    'GetFormularyDetailDisplayHTML' => 'GetFormularyDetailDisplayHTML',
                                    'GetFormularyDetailDisplayHTMLResponse' => 'GetFormularyDetailDisplayHTMLResponse',
                                    'GetFormularyDetailDisplaySupplyHTML' => 'GetFormularyDetailDisplaySupplyHTML',
                                    'GetFormularyDetailDisplaySupplyHTMLResponse' => 'GetFormularyDetailDisplaySupplyHTMLResponse',
                                    'GetFormularyCoverageInfoDS' => 'GetFormularyCoverageInfoDS',
                                    'GetFormularyCoverageInfoDSResponse' => 'GetFormularyCoverageInfoDSResponse',
                                    'GetFormularyCoverageInfoDSResult' => 'GetFormularyCoverageInfoDSResult',
                                    'GetFormularyCopayInfoDS' => 'GetFormularyCopayInfoDS',
                                    'GetFormularyCopayInfoDSResponse' => 'GetFormularyCopayInfoDSResponse',
                                    'GetFormularyCopayInfoDSResult' => 'GetFormularyCopayInfoDSResult',
                                    'GetFormularyDrugAltsDS' => 'GetFormularyDrugAltsDS',
                                    'GetFormularyDrugAltsDSResponse' => 'GetFormularyDrugAltsDSResponse',
                                    'GetFormularyDrugAltsDSResult' => 'GetFormularyDrugAltsDSResult',
                                    'GetDrugAltsDS' => 'GetDrugAltsDS',
                                    'GetDrugAltsDSResponse' => 'GetDrugAltsDSResponse',
                                    'GetDrugAltsDSResult' => 'GetDrugAltsDSResult',
                                    'GetDrugAltsPerDrugClassDS' => 'GetDrugAltsPerDrugClassDS',
                                    'GetDrugAltsPerDrugClassDSResponse' => 'GetDrugAltsPerDrugClassDSResponse',
                                    'GetDrugAltsPerDrugClassDSResult' => 'GetDrugAltsPerDrugClassDSResult',
                                    'GetDrugClassesDS' => 'GetDrugClassesDS',
                                    'GetDrugClassesDSResponse' => 'GetDrugClassesDSResponse',
                                    'GetDrugClassesDSResult' => 'GetDrugClassesDSResult',
                                    'GetDrugConditionsDS' => 'GetDrugConditionsDS',
                                    'GetDrugConditionsDSResponse' => 'GetDrugConditionsDSResponse',
                                    'GetDrugConditionsDSResult' => 'GetDrugConditionsDSResult',
                                    'GetDrugWarningsDS' => 'GetDrugWarningsDS',
                                    'GetDrugWarningsDSResponse' => 'GetDrugWarningsDSResponse',
                                    'GetDrugWarningsDSResult' => 'GetDrugWarningsDSResult',
                                    'GetDrugWarnPregLactDS' => 'GetDrugWarnPregLactDS',
                                    'GetDrugWarnPregLactDSResponse' => 'GetDrugWarnPregLactDSResponse',
                                    'GetDrugWarnPregLactDSResult' => 'GetDrugWarnPregLactDSResult',
                                    'GetDrugWarningLabelsDS' => 'GetDrugWarningLabelsDS',
                                    'GetDrugWarningLabelsDSResponse' => 'GetDrugWarningLabelsDSResponse',
                                    'GetDrugWarningLabelsDSResult' => 'GetDrugWarningLabelsDSResult',
                                    'GetDrugPatLeafletURLByProduct' => 'GetDrugPatLeafletURLByProduct',
                                    'Language' => 'Language',
                                    'GetDrugPatLeafletURLByProductResponse' => 'GetDrugPatLeafletURLByProductResponse',
                                    'GetDrugPatLeafletURL' => 'GetDrugPatLeafletURL',
                                    'GetDrugPatLeafletURLResponse' => 'GetDrugPatLeafletURLResponse',
                                    'GetDrugPatLeafletURLByGeneric' => 'GetDrugPatLeafletURLByGeneric',
                                    'GetDrugPatLeafletURLByGenericResponse' => 'GetDrugPatLeafletURLByGenericResponse',
                                    'GetDrugDosingRefDS' => 'GetDrugDosingRefDS',
                                    'GetDrugDosingRefDSResponse' => 'GetDrugDosingRefDSResponse',
                                    'GetDrugDosingRefDSResult' => 'GetDrugDosingRefDSResult',
                                    'GetDrugAllergyInterDS' => 'GetDrugAllergyInterDS',
                                    'GetDrugAllergyInterDSResponse' => 'GetDrugAllergyInterDSResponse',
                                    'GetDrugAllergyInterDSResult' => 'GetDrugAllergyInterDSResult',
                                    'GetDrugDosingDataDS' => 'GetDrugDosingDataDS',
                                    'GetDrugDosingDataDSResponse' => 'GetDrugDosingDataDSResponse',
                                    'GetDrugDosingDataDSResult' => 'GetDrugDosingDataDSResult',
                                    'GetDrugDosingDataPerDS' => 'GetDrugDosingDataPerDS',
                                    'GetDrugDosingDataPerDSResponse' => 'GetDrugDosingDataPerDSResponse',
                                    'GetDrugDosingDataPerDSResult' => 'GetDrugDosingDataPerDSResult',
                                    'GetDrugConditionInterDS' => 'GetDrugConditionInterDS',
                                    'GetDrugConditionInterDSResponse' => 'GetDrugConditionInterDSResponse',
                                    'GetDrugConditionInterDSResult' => 'GetDrugConditionInterDSResult',
                                    'GetDrugDrugInterDS' => 'GetDrugDrugInterDS',
                                    'GetDrugDrugInterDSResponse' => 'GetDrugDrugInterDSResponse',
                                    'GetDrugDrugInterDSResult' => 'GetDrugDrugInterDSResult',
                                    'GetDrugFoodInterDS' => 'GetDrugFoodInterDS',
                                    'GetDrugFoodInterDSResponse' => 'GetDrugFoodInterDSResponse',
                                    'GetDrugFoodInterDSResult' => 'GetDrugFoodInterDSResult',
                                    'GetDrugDupTherClassDS' => 'GetDrugDupTherClassDS',
                                    'GetDrugDupTherClassDSResponse' => 'GetDrugDupTherClassDSResponse',
                                    'GetDrugDupTherClassDSResult' => 'GetDrugDupTherClassDSResult',
                                    'GetDrugSideEffectsDS' => 'GetDrugSideEffectsDS',
                                    'GetDrugSideEffectsDSResponse' => 'GetDrugSideEffectsDSResponse',
                                    'GetDrugSideEffectsDSResult' => 'GetDrugSideEffectsDSResult',
                                    'GetDrugIdListbyDupTherClassDS' => 'GetDrugIdListbyDupTherClassDS',
                                    'GetDrugIdListbyDupTherClassDSResponse' => 'GetDrugIdListbyDupTherClassDSResponse',
                                    'GetDrugIdListbyDupTherClassDSResult' => 'GetDrugIdListbyDupTherClassDSResult',
                                    'GetDrugGenListbyDupTherClassDS' => 'GetDrugGenListbyDupTherClassDS',
                                    'GetDrugGenListbyDupTherClassDSResponse' => 'GetDrugGenListbyDupTherClassDSResponse',
                                    'GetDrugGenListbyDupTherClassDSResult' => 'GetDrugGenListbyDupTherClassDSResult',
                                    'GetVaccineNDCPackagingDS' => 'GetVaccineNDCPackagingDS',
                                    'GetVaccineNDCPackagingDSResponse' => 'GetVaccineNDCPackagingDSResponse',
                                    'GetVaccineNDCPackagingDSResult' => 'GetVaccineNDCPackagingDSResult',
                                    'GetDrugNDCPackagingDS' => 'GetDrugNDCPackagingDS',
                                    'GetDrugNDCPackagingDSResponse' => 'GetDrugNDCPackagingDSResponse',
                                    'GetDrugNDCPackagingDSResult' => 'GetDrugNDCPackagingDSResult',
                                    'GetDrugNDCPackagingSummaryDS' => 'GetDrugNDCPackagingSummaryDS',
                                    'GetDrugNDCPackagingSummaryDSResponse' => 'GetDrugNDCPackagingSummaryDSResponse',
                                    'GetDrugNDCPackagingSummaryDSResult' => 'GetDrugNDCPackagingSummaryDSResult',
                                    'GetDrugNDCPackagingSummaryByUnqIdDS' => 'GetDrugNDCPackagingSummaryByUnqIdDS',
                                    'GetDrugNDCPackagingSummaryByUnqIdDSResponse' => 'GetDrugNDCPackagingSummaryByUnqIdDSResponse',
                                    'GetDrugNDCPackagingSummaryByUnqIdDSResult' => 'GetDrugNDCPackagingSummaryByUnqIdDSResult',
                                    'GetDrugDS' => 'GetDrugDS',
                                    'GetDrugDSResponse' => 'GetDrugDSResponse',
                                    'GetDrugDSResult' => 'GetDrugDSResult',
                                    'GetDrugInfoCSADS' => 'GetDrugInfoCSADS',
                                    'GetDrugInfoCSADSResponse' => 'GetDrugInfoCSADSResponse',
                                    'GetDrugInfoCSADSResult' => 'GetDrugInfoCSADSResult',
                                    'GetDrugByNDCDS' => 'GetDrugByNDCDS',
                                    'GetDrugByNDCDSResponse' => 'GetDrugByNDCDSResponse',
                                    'GetDrugByNDCDSResult' => 'GetDrugByNDCDSResult',
                                    'GetDrugAndFormularyDS' => 'GetDrugAndFormularyDS',
                                    'GetDrugAndFormularyDSResponse' => 'GetDrugAndFormularyDSResponse',
                                    'GetDrugAndFormularyDSResult' => 'GetDrugAndFormularyDSResult',
                                    'GetDrugAndFormularySupplyDS' => 'GetDrugAndFormularySupplyDS',
                                    'GetDrugAndFormularySupplyDSResponse' => 'GetDrugAndFormularySupplyDSResponse',
                                    'GetDrugAndFormularySupplyDSResult' => 'GetDrugAndFormularySupplyDSResult',
                                    'GetFrequencyDS' => 'GetFrequencyDS',
                                    'GetFrequencyDSResponse' => 'GetFrequencyDSResponse',
                                    'GetFrequencyDSResult' => 'GetFrequencyDSResult',
                                    'GetDirectionsDS' => 'GetDirectionsDS',
                                    'GetDirectionsDSResponse' => 'GetDirectionsDSResponse',
                                    'GetDirectionsDSResult' => 'GetDirectionsDSResult',
                                    'GetDrugQtyUofMDS' => 'GetDrugQtyUofMDS',
                                    'GetDrugQtyUofMDSResponse' => 'GetDrugQtyUofMDSResponse',
                                    'GetDrugQtyUofMDSResult' => 'GetDrugQtyUofMDSResult',
                                    'ConvertDrugIds' => 'ConvertDrugIds',
                                    'ConvertDrugIdsResponse' => 'ConvertDrugIdsResponse',
                                    'ConvertDrugIdsResult' => 'ConvertDrugIdsResult',
                                    'GetAlertsAllDS' => 'GetAlertsAllDS',
                                    'GetAlertsAllDSResponse' => 'GetAlertsAllDSResponse',
                                    'GetAlertsAllDSResult' => 'GetAlertsAllDSResult',
                                    'GetPharmacyDS' => 'GetPharmacyDS',
                                    'Pharmacy' => 'Pharmacy',
                                    'GetPharmacyDSResponse' => 'GetPharmacyDSResponse',
                                    'GetPharmacyDSResult' => 'GetPharmacyDSResult',
                                    'GetPharmacyXML' => 'GetPharmacyXML',
                                    'GetPharmacyXMLResponse' => 'GetPharmacyXMLResponse',
                                    'GetPharmacyObj' => 'GetPharmacyObj',
                                    'GetPharmacyObjResponse' => 'GetPharmacyObjResponse',
                                    'GetAllergyDS' => 'GetAllergyDS',
                                    'GetAllergyDSResponse' => 'GetAllergyDSResponse',
                                    'GetAllergyDSResult' => 'GetAllergyDSResult',
                                    'GetAllergyXML' => 'GetAllergyXML',
                                    'GetAllergyXMLResponse' => 'GetAllergyXMLResponse',
                                    'GetConditionDS' => 'GetConditionDS',
                                    'GetConditionDSResponse' => 'GetConditionDSResponse',
                                    'GetConditionDSResult' => 'GetConditionDSResult',
                                    'GetRefillRequestsDS' => 'GetRefillRequestsDS',
                                    'GetRefillRequestsDSResponse' => 'GetRefillRequestsDSResponse',
                                    'GetRefillRequestsDSResult' => 'GetRefillRequestsDSResult',
                                    'GetRefillRequestsAllDS' => 'GetRefillRequestsAllDS',
                                    'RXRefillReqStatus' => 'RXRefillReqStatus',
                                    'GetRefillRequestsAllDSResponse' => 'GetRefillRequestsAllDSResponse',
                                    'GetRefillRequestsAllDSResult' => 'GetRefillRequestsAllDSResult',
                                    'GetRefillRequestsAllObj' => 'GetRefillRequestsAllObj',
                                    'GetRefillRequestsAllObjResponse' => 'GetRefillRequestsAllObjResponse',
                                    'RefillRequestRecord' => 'RefillRequestRecord',
                                    'GetRefillRequestsCount' => 'GetRefillRequestsCount',
                                    'GetRefillRequestsCountResponse' => 'GetRefillRequestsCountResponse',
                                    'GetRefillRequestDetailDS' => 'GetRefillRequestDetailDS',
                                    'GetRefillRequestDetailDSResponse' => 'GetRefillRequestDetailDSResponse',
                                    'GetRefillRequestDetailDSResult' => 'GetRefillRequestDetailDSResult',
                                    'GetRefillResponseDenialCodesDS' => 'GetRefillResponseDenialCodesDS',
                                    'GetRefillResponseDenialCodesDSResponse' => 'GetRefillResponseDenialCodesDSResponse',
                                    'GetRefillResponseDenialCodesDSResult' => 'GetRefillResponseDenialCodesDSResult',
                                    'GetPatientEligibilityDS' => 'GetPatientEligibilityDS',
                                    'Patient' => 'Patient',
                                    'Prescriber' => 'Prescriber',
                                    'GetPatientEligibilityDSResponse' => 'GetPatientEligibilityDSResponse',
                                    'GetPatientEligibilityDSResult' => 'GetPatientEligibilityDSResult',
                                    'GetPatientEligibilityXML' => 'GetPatientEligibilityXML',
                                    'GetPatientEligibilityXMLResponse' => 'GetPatientEligibilityXMLResponse',
                                    'GetPatientEligibilityOnFileDS' => 'GetPatientEligibilityOnFileDS',
                                    'GetPatientEligibilityOnFileDSResponse' => 'GetPatientEligibilityOnFileDSResponse',
                                    'GetPatientEligibilityOnFileDSResult' => 'GetPatientEligibilityOnFileDSResult',
                                    'GetPatientEligibilityOnFileXML' => 'GetPatientEligibilityOnFileXML',
                                    'GetPatientEligibilityOnFileXMLResponse' => 'GetPatientEligibilityOnFileXMLResponse',
                                    'GetPatientRxHistoryRequestDS' => 'GetPatientRxHistoryRequestDS',
                                    'GetPatientRxHistoryRequestDSResponse' => 'GetPatientRxHistoryRequestDSResponse',
                                    'GetPatientRxHistoryRequestDSResult' => 'GetPatientRxHistoryRequestDSResult',
                                    'CheckPrescriberRegistration' => 'CheckPrescriberRegistration',
                                    'CheckPrescriberRegistrationResponse' => 'CheckPrescriberRegistrationResponse',
                                    'CheckPrescriberRegistrationPerId' => 'CheckPrescriberRegistrationPerId',
                                    'CheckPrescriberRegistrationPerIdResponse' => 'CheckPrescriberRegistrationPerIdResponse',
                                    'RegisterPrescriber' => 'RegisterPrescriber',
                                    'SurescriptsServices' => 'SurescriptsServices',
                                    'RegisterPrescriberResponse' => 'RegisterPrescriberResponse',
                                    'eSendRefillReqResponse' => 'eSendRefillReqResponse',
                                    'RXRefillResponse' => 'RXRefillResponse',
                                    'eSendRefillReqResponseResponse' => 'eSendRefillReqResponseResponse',
                                    'GetNewMessageAlertsCount' => 'GetNewMessageAlertsCount',
                                    'GetNewMessageAlertsCountResponse' => 'GetNewMessageAlertsCountResponse',
                                    'GetNewMessageAlerts' => 'GetNewMessageAlerts',
                                    'GetNewMessageAlertsResponse' => 'GetNewMessageAlertsResponse',
                                    'GetNewMessageAlertsResult' => 'GetNewMessageAlertsResult',
                                    'UpdateErrorMessageAlertComplete' => 'UpdateErrorMessageAlertComplete',
                                    'UpdateErrorMessageAlertCompleteResponse' => 'UpdateErrorMessageAlertCompleteResponse',
                                    'GetErrorMessageAlertsDS' => 'GetErrorMessageAlertsDS',
                                    'GetErrorMessageAlertsDSResponse' => 'GetErrorMessageAlertsDSResponse',
                                    'GetErrorMessageAlertsDSResult' => 'GetErrorMessageAlertsDSResult',
                                    'GetErrorMessageAlertsCount' => 'GetErrorMessageAlertsCount',
                                    'GetErrorMessageAlertsCountResponse' => 'GetErrorMessageAlertsCountResponse',
                                    'GeteSendMessageStatus' => 'GeteSendMessageStatus',
                                    'GeteSendMessageStatusResponse' => 'GeteSendMessageStatusResponse',
                                    'GeteSendRefillMessageStatus' => 'GeteSendRefillMessageStatus',
                                    'GeteSendRefillMessageStatusResponse' => 'GeteSendRefillMessageStatusResponse',
                                    'eSendNewRx' => 'eSendNewRx',
                                    'Prescription' => 'Prescription',
                                    'eSendNewRxResponse' => 'eSendNewRxResponse',
                                    'RXRouteStatus' => 'RXRouteStatus',
                                    'UpdatePrinted' => 'UpdatePrinted',
                                    'UpdatePrintedResponse' => 'UpdatePrintedResponse',
                                    'UpdateDataForScreens' => 'UpdateDataForScreens',
                                    'Location' => 'Location',
                                    'MedicationRecord' => 'MedicationRecord',
                                    'RXStatus' => 'RXStatus',
                                    'AllergyRecord' => 'AllergyRecord',
                                    'ConditionRecord' => 'ConditionRecord',
                                    'VitalRecord' => 'VitalRecord',
                                    'UpdateDataForScreensResponse' => 'UpdateDataForScreensResponse',
                                    'GetPrescriptionsForPatient' => 'GetPrescriptionsForPatient',
                                    'GetPrescriptionsForPatientResponse' => 'GetPrescriptionsForPatientResponse',
                                    'GetPrescriptionsForPatientResult' => 'GetPrescriptionsForPatientResult',
                                    'GetPrescriptionsForPatientXML' => 'GetPrescriptionsForPatientXML',
                                    'GetPrescriptionsForPatientXMLResponse' => 'GetPrescriptionsForPatientXMLResponse',
                                    'GetPrescriptionsForPatientObj' => 'GetPrescriptionsForPatientObj',
                                    'GetPrescriptionsForPatientObjResponse' => 'GetPrescriptionsForPatientObjResponse',
                                    'GetAllergiesForPatient' => 'GetAllergiesForPatient',
                                    'GetAllergiesForPatientResponse' => 'GetAllergiesForPatientResponse',
                                    'GetAllergiesForPatientResult' => 'GetAllergiesForPatientResult',
                                    'GetAllergiesForPatientObj' => 'GetAllergiesForPatientObj',
                                    'GetAllergiesForPatientObjResponse' => 'GetAllergiesForPatientObjResponse',
                                    'GetPrescriberAllowedSuffixStr' => 'GetPrescriberAllowedSuffixStr',
                                    'GetPrescriberAllowedSuffixStrResponse' => 'GetPrescriberAllowedSuffixStrResponse',
                                    'GetPrescriptionsByPrescriber' => 'GetPrescriptionsByPrescriber',
                                    'GetPrescriptionsByPrescriberResponse' => 'GetPrescriptionsByPrescriberResponse',
                                    'GetPrescriptionsByPrescriberResult' => 'GetPrescriptionsByPrescriberResult',
                                    'UpdateDataPatientVitals' => 'UpdateDataPatientVitals',
                                    'UpdateDataPatientVitalsResponse' => 'UpdateDataPatientVitalsResponse',
                                   );

  public function rx($wsdl = "http://localhost/RxWs/rx.asmx?wsdl", $options = array()) {
    foreach(self::$classmap as $key => $value) {
      if(!isset($options['classmap'][$key])) {
        $options['classmap'][$key] = $value;
      }
    }
    parent::__construct($wsdl, $options);
  }

  /**
   * Test Method-Will validate and return Success or Fail for connect status. 
   *
   * @param WebServiceTest $parameters
   * @return WebServiceTestResponse
   */
  public function WebServiceTest(WebServiceTest $parameters) {
    return $this->__soapCall('WebServiceTest', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Search the drug lookup list-returns dataset of found drugs. 
   *
   * @param GetDrugListDS $parameters
   * @return GetDrugListDSResponse
   */
  public function GetDrugListDS(GetDrugListDS $parameters) {
    return $this->__soapCall('GetDrugListDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Search the drug lookup list-returns dataset of found drugs including dea class codes at 
   * the state level. 
   *
   * @param GetDrugListWithStateDeaCodesDS $parameters
   * @return GetDrugListWithStateDeaCodesDSResponse
   */
  public function GetDrugListWithStateDeaCodesDS(GetDrugListWithStateDeaCodesDS $parameters) {
    return $this->__soapCall('GetDrugListWithStateDeaCodesDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Search the drug lookup list-returns dataset of found drugs including dea class codes at 
   * the state level and any vouchers that might be on file per subaccount and patient. 
   *
   * @param GetDrugListwStateDeaPlusVoucherDS $parameters
   * @return GetDrugListwStateDeaPlusVoucherDSResponse
   */
  public function GetDrugListwStateDeaPlusVoucherDS(GetDrugListwStateDeaPlusVoucherDS $parameters) {
    return $this->__soapCall('GetDrugListwStateDeaPlusVoucherDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Search the drug lookup list-returns dataset of found drugs - with paging - pass in pagesize 
   * and page number to return. 
   *
   * @param GetDrugListWithPagingDS $parameters
   * @return GetDrugListWithPagingDSResponse
   */
  public function GetDrugListWithPagingDS(GetDrugListWithPagingDS $parameters) {
    return $this->__soapCall('GetDrugListWithPagingDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WSPartners:Get an HTML Formatted string that includes Coverage,Copay and Alternative details 
   * for a given drug and formulary. 
   *
   * @param GetFormularyDetailDisplayHTML $parameters
   * @return GetFormularyDetailDisplayHTMLResponse
   */
  public function GetFormularyDetailDisplayHTML(GetFormularyDetailDisplayHTML $parameters) {
    return $this->__soapCall('GetFormularyDetailDisplayHTML', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WSPartners:Get an HTML Formatted string that includes Coverage,Copay and Alternative details 
   * for a given supply and formulary. 
   *
   * @param GetFormularyDetailDisplaySupplyHTML $parameters
   * @return GetFormularyDetailDisplaySupplyHTMLResponse
   */
  public function GetFormularyDetailDisplaySupplyHTML(GetFormularyDetailDisplaySupplyHTML $parameters) {
    return $this->__soapCall('GetFormularyDetailDisplaySupplyHTML', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WSPartners:Get the PBM formulary coverage extra details for certain drug and formulary. 
   * Pass in either DrugId (for drug products) or NDC (for supplies).  If both are passed DrugId 
   * will be used. 
   *
   * @param GetFormularyCoverageInfoDS $parameters
   * @return GetFormularyCoverageInfoDSResponse
   */
  public function GetFormularyCoverageInfoDS(GetFormularyCoverageInfoDS $parameters) {
    return $this->__soapCall('GetFormularyCoverageInfoDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WSPartners:Get the PBM formulary copay info for certain drug and formulary. Pass in either 
   * DrugId or NDC.  If both are passed DrugId will be used. 
   *
   * @param GetFormularyCopayInfoDS $parameters
   * @return GetFormularyCopayInfoDSResponse
   */
  public function GetFormularyCopayInfoDS(GetFormularyCopayInfoDS $parameters) {
    return $this->__soapCall('GetFormularyCopayInfoDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WSPartners:Get the PBM formulary alternative drugs for certain drug and formulary - this 
   * returns a list of drugs that  are PBM suggested alternates per the non-covered passed 
   * in drug (not all pbms/drugs will have alts). Pass in Drug Id or Supply NDC for supply 
   * alts - if both are passed function will use Drug Id 
   *
   * @param GetFormularyDrugAltsDS $parameters
   * @return GetFormularyDrugAltsDSResponse
   */
  public function GetFormularyDrugAltsDS(GetFormularyDrugAltsDS $parameters) {
    return $this->__soapCall('GetFormularyDrugAltsDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Get a list of alternate drugs (same drug product) per the given drug.  Return list will 
   * be sorted by formulary status - most preferred first. 
   *
   * @param GetDrugAltsDS $parameters
   * @return GetDrugAltsDSResponse
   */
  public function GetDrugAltsDS(GetDrugAltsDS $parameters) {
    return $this->__soapCall('GetDrugAltsDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Get a list of alternate drugs in the same drug class per the given drug.  Return list 
   * will be sorted by formulary status - most preferred first. 
   *
   * @param GetDrugAltsPerDrugClassDS $parameters
   * @return GetDrugAltsPerDrugClassDSResponse
   */
  public function GetDrugAltsPerDrugClassDS(GetDrugAltsPerDrugClassDS $parameters) {
    return $this->__soapCall('GetDrugAltsPerDrugClassDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Returns dataset of drug classes a particular generic drug belongs to. 
   *
   * @param GetDrugClassesDS $parameters
   * @return GetDrugClassesDSResponse
   */
  public function GetDrugClassesDS(GetDrugClassesDS $parameters) {
    return $this->__soapCall('GetDrugClassesDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Returns dataset of conditions drug product treats 
   *
   * @param GetDrugConditionsDS $parameters
   * @return GetDrugConditionsDSResponse
   */
  public function GetDrugConditionsDS(GetDrugConditionsDS $parameters) {
    return $this->__soapCall('GetDrugConditionsDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Return general drug warnings for generic drug id 
   *
   * @param GetDrugWarningsDS $parameters
   * @return GetDrugWarningsDSResponse
   */
  public function GetDrugWarningsDS(GetDrugWarningsDS $parameters) {
    return $this->__soapCall('GetDrugWarningsDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetDrugWarnPregLactDS $parameters
   * @return GetDrugWarnPregLactDSResponse
   */
  public function GetDrugWarnPregLactDS(GetDrugWarnPregLactDS $parameters) {
    return $this->__soapCall('GetDrugWarnPregLactDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetDrugWarningLabelsDS $parameters
   * @return GetDrugWarningLabelsDSResponse
   */
  public function GetDrugWarningLabelsDS(GetDrugWarningLabelsDS $parameters) {
    return $this->__soapCall('GetDrugWarningLabelsDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * For given drug product id and language will return URL to patient leaflet 
   *
   * @param GetDrugPatLeafletURLByProduct $parameters
   * @return GetDrugPatLeafletURLByProductResponse
   */
  public function GetDrugPatLeafletURLByProduct(GetDrugPatLeafletURLByProduct $parameters) {
    return $this->__soapCall('GetDrugPatLeafletURLByProduct', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * For given unqiue drug id and language will return URL to patient leaflet 
   *
   * @param GetDrugPatLeafletURL $parameters
   * @return GetDrugPatLeafletURLResponse
   */
  public function GetDrugPatLeafletURL(GetDrugPatLeafletURL $parameters) {
    return $this->__soapCall('GetDrugPatLeafletURL', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * For given generic id and language will return URL to patient leaflet 
   *
   * @param GetDrugPatLeafletURLByGeneric $parameters
   * @return GetDrugPatLeafletURLByGenericResponse
   */
  public function GetDrugPatLeafletURLByGeneric(GetDrugPatLeafletURLByGeneric $parameters) {
    return $this->__soapCall('GetDrugPatLeafletURLByGeneric', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Returns a dataset of drug dosing reference text per given generic drug id. 
   *
   * @param GetDrugDosingRefDS $parameters
   * @return GetDrugDosingRefDSResponse
   */
  public function GetDrugDosingRefDS(GetDrugDosingRefDS $parameters) {
    return $this->__soapCall('GetDrugDosingRefDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Returns a list of potential allergy interactions for the given drug combo id 
   *
   * @param GetDrugAllergyInterDS $parameters
   * @return GetDrugAllergyInterDSResponse
   */
  public function GetDrugAllergyInterDS(GetDrugAllergyInterDS $parameters) {
    return $this->__soapCall('GetDrugAllergyInterDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Get Drug Dosing Data - returns max and min recommended dosing including age, gender, weight 
   * for both daily dosing and per dose 
   *
   * @param GetDrugDosingDataDS $parameters
   * @return GetDrugDosingDataDSResponse
   */
  public function GetDrugDosingDataDS(GetDrugDosingDataDS $parameters) {
    return $this->__soapCall('GetDrugDosingDataDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Get Drug Dosing Data rows per patient age, weight, gender and or condition 
   *
   * @param GetDrugDosingDataPerDS $parameters
   * @return GetDrugDosingDataPerDSResponse
   */
  public function GetDrugDosingDataPerDS(GetDrugDosingDataPerDS $parameters) {
    return $this->__soapCall('GetDrugDosingDataPerDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Returns all potential condition/disease interactions per given drug 
   *
   * @param GetDrugConditionInterDS $parameters
   * @return GetDrugConditionInterDSResponse
   */
  public function GetDrugConditionInterDS(GetDrugConditionInterDS $parameters) {
    return $this->__soapCall('GetDrugConditionInterDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Returns all potential drug drug interactions per given drug 
   *
   * @param GetDrugDrugInterDS $parameters
   * @return GetDrugDrugInterDSResponse
   */
  public function GetDrugDrugInterDS(GetDrugDrugInterDS $parameters) {
    return $this->__soapCall('GetDrugDrugInterDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Returns dataset of potential drug food interactions and general drug food related warnings 
   * for given generic drug id. 
   *
   * @param GetDrugFoodInterDS $parameters
   * @return GetDrugFoodInterDSResponse
   */
  public function GetDrugFoodInterDS(GetDrugFoodInterDS $parameters) {
    return $this->__soapCall('GetDrugFoodInterDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Get Drug Duplicate Therapy class for given unique combo drug id (allowing to give warnings 
   * if patient is already taking something similar) 
   *
   * @param GetDrugDupTherClassDS $parameters
   * @return GetDrugDupTherClassDSResponse
   */
  public function GetDrugDupTherClassDS(GetDrugDupTherClassDS $parameters) {
    return $this->__soapCall('GetDrugDupTherClassDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Get general side effects dataset for the given generic drug id 
   *
   * @param GetDrugSideEffectsDS $parameters
   * @return GetDrugSideEffectsDSResponse
   */
  public function GetDrugSideEffectsDS(GetDrugSideEffectsDS $parameters) {
    return $this->__soapCall('GetDrugSideEffectsDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * For given duplicate therapy class get list of drugs (unique drug combos) in the same class 
   * 
   *
   * @param GetDrugIdListbyDupTherClassDS $parameters
   * @return GetDrugIdListbyDupTherClassDSResponse
   */
  public function GetDrugIdListbyDupTherClassDS(GetDrugIdListbyDupTherClassDS $parameters) {
    return $this->__soapCall('GetDrugIdListbyDupTherClassDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * For given duplicate therapy class get list of drugs (generic drugs) in the same class 
   * 
   *
   * @param GetDrugGenListbyDupTherClassDS $parameters
   * @return GetDrugGenListbyDupTherClassDSResponse
   */
  public function GetDrugGenListbyDupTherClassDS(GetDrugGenListbyDupTherClassDS $parameters) {
    return $this->__soapCall('GetDrugGenListbyDupTherClassDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Immunization Partners: For given vaccine search name and optional manufacturer, return 
   * list of vaccines,manufacturers and packaging available for the drug (returns active only) 
   * 
   *
   * @param GetVaccineNDCPackagingDS $parameters
   * @return GetVaccineNDCPackagingDSResponse
   */
  public function GetVaccineNDCPackagingDS(GetVaccineNDCPackagingDS $parameters) {
    return $this->__soapCall('GetVaccineNDCPackagingDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * For given drug product id, return dataset of NDC codes, manufacturers and packaging available 
   * for the drug 
   *
   * @param GetDrugNDCPackagingDS $parameters
   * @return GetDrugNDCPackagingDSResponse
   */
  public function GetDrugNDCPackagingDS(GetDrugNDCPackagingDS $parameters) {
    return $this->__soapCall('GetDrugNDCPackagingDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * For given drug product id, return dataset of  packaging sizes available for the drug. 
   * Returns distinct packaging sizes. 
   *
   * @param GetDrugNDCPackagingSummaryDS $parameters
   * @return GetDrugNDCPackagingSummaryDSResponse
   */
  public function GetDrugNDCPackagingSummaryDS(GetDrugNDCPackagingSummaryDS $parameters) {
    return $this->__soapCall('GetDrugNDCPackagingSummaryDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * For given drug product id, return dataset of packaging sizes available for the drug. Returns 
   * distinct packaging sizes. 
   *
   * @param GetDrugNDCPackagingSummaryByUnqIdDS $parameters
   * @return GetDrugNDCPackagingSummaryByUnqIdDSResponse
   */
  public function GetDrugNDCPackagingSummaryByUnqIdDS(GetDrugNDCPackagingSummaryByUnqIdDS $parameters) {
    return $this->__soapCall('GetDrugNDCPackagingSummaryByUnqIdDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Get drug information for given drug id - can pass in either Generic Drug Id, Product Drug 
   * Id or a Unique Combination Id or the RXNorm CUI code for the drug 
   *
   * @param GetDrugDS $parameters
   * @return GetDrugDSResponse
   */
  public function GetDrugDS(GetDrugDS $parameters) {
    return $this->__soapCall('GetDrugDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Get drug information and csa schedule/dea class code for given drug id and doctor/pharm 
   * state - can pass in either Generic Drug Id, Product Drug Id or a Unique Combination Id 
   * or the RXNorm CUI code for the drug 
   *
   * @param GetDrugInfoCSADS $parameters
   * @return GetDrugInfoCSADSResponse
   */
  public function GetDrugInfoCSADS(GetDrugInfoCSADS $parameters) {
    return $this->__soapCall('GetDrugInfoCSADS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Get basic drug information for drug based on NDC code 
   *
   * @param GetDrugByNDCDS $parameters
   * @return GetDrugByNDCDSResponse
   */
  public function GetDrugByNDCDS(GetDrugByNDCDS $parameters) {
    return $this->__soapCall('GetDrugByNDCDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WSPartners:Get drug information including formulary for given drug id - can pass in either 
   * Generic Drug Id, Product Drug Id or a Unique Combination Id or the RXNorm CUI code for 
   * the drug 
   *
   * @param GetDrugAndFormularyDS $parameters
   * @return GetDrugAndFormularyDSResponse
   */
  public function GetDrugAndFormularyDS(GetDrugAndFormularyDS $parameters) {
    return $this->__soapCall('GetDrugAndFormularyDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WSPartners: Get drug supply information including formulary for given supply ndc 
   *
   * @param GetDrugAndFormularySupplyDS $parameters
   * @return GetDrugAndFormularySupplyDSResponse
   */
  public function GetDrugAndFormularySupplyDS(GetDrugAndFormularySupplyDS $parameters) {
    return $this->__soapCall('GetDrugAndFormularySupplyDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Return dataset of common prescription frequencies/sigs, e.g. Once a Day.  Returns both 
   * abbreviated (BID) and full (Twice a day) 
   *
   * @param GetFrequencyDS $parameters
   * @return GetFrequencyDSResponse
   */
  public function GetFrequencyDS(GetFrequencyDS $parameters) {
    return $this->__soapCall('GetFrequencyDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Return dataset of common prescription full directions including dosage and frequency 
   *
   * @param GetDirectionsDS $parameters
   * @return GetDirectionsDSResponse
   */
  public function GetDirectionsDS(GetDirectionsDS $parameters) {
    return $this->__soapCall('GetDirectionsDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Returns dataset of medication dispense number quantity unit of measure. EG. Caplets - 
   * each is associated with a 2 character code used for Disp# qualifier when routing prescriptions. 
   * 
   *
   * @param GetDrugQtyUofMDS $parameters
   * @return GetDrugQtyUofMDSResponse
   */
  public function GetDrugQtyUofMDS(GetDrugQtyUofMDS $parameters) {
    return $this->__soapCall('GetDrugQtyUofMDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * This routine will return an xml dataset of drugs for the passed in array of ids. The return 
   * data should contain one row per passed in ID in the array, if an id is not found it will 
   * return a row  that includes MDTB Ids: 000 and a drug description of 'not found'. Drug 
   * Id Source should be the source of the drug id - use the source code as found in RxNorm 
   * (NDDF, RXNorm, MMX, etc). 
   *
   * @param ConvertDrugIds $parameters
   * @return ConvertDrugIdsResponse
   */
  public function ConvertDrugIds(ConvertDrugIds $parameters) {
    return $this->__soapCall('ConvertDrugIds', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * This routine will return all potential interactions for given Unique Drug Id  and passed 
   * in array of patients allergies (allergy ids), current medications (generic med id)  and 
   * conditions (condition ids and/or icd9 codes).  Boolean Parameters allow for setting which 
   * alerts to return (for turning on and off only  showing certain kinds of alerts.)  The 
   * dataset returned will include requested Drug-Drug, Drug-Allergy, Drug-Condition and DupTherapy 
   * all  in one table formatted for display to the end-user. 
   *
   * @param GetAlertsAllDS $parameters
   * @return GetAlertsAllDSResponse
   */
  public function GetAlertsAllDS(GetAlertsAllDS $parameters) {
    return $this->__soapCall('GetAlertsAllDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Return a dataset of pharmacies available for routing to  Pass in pharmacy object with 
   * search details and boolean variables for which type of pharmacies to include. 
   *
   * @param GetPharmacyDS $parameters
   * @return GetPharmacyDSResponse
   */
  public function GetPharmacyDS(GetPharmacyDS $parameters) {
    return $this->__soapCall('GetPharmacyDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Return an xml string of pharmacies available for routing to  Pass in pharmacy object with 
   * search details and boolean variables for which type of pharmacies to include. 
   *
   * @param GetPharmacyXML $parameters
   * @return GetPharmacyXMLResponse
   */
  public function GetPharmacyXML(GetPharmacyXML $parameters) {
    return $this->__soapCall('GetPharmacyXML', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Return pharmacy object array of pharmacies available for routing to  Pass in pharmacy 
   * object with search details and boolean variables for which type of pharmacies to include. 
   * 
   *
   * @param GetPharmacyObj $parameters
   * @return GetPharmacyObjResponse
   */
  public function GetPharmacyObj(GetPharmacyObj $parameters) {
    return $this->__soapCall('GetPharmacyObj', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Return allergy dataset for given search term. 
   *
   * @param GetAllergyDS $parameters
   * @return GetAllergyDSResponse
   */
  public function GetAllergyDS(GetAllergyDS $parameters) {
    return $this->__soapCall('GetAllergyDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Return allergy xml string for given search term. 
   *
   * @param GetAllergyXML $parameters
   * @return GetAllergyXMLResponse
   */
  public function GetAllergyXML(GetAllergyXML $parameters) {
    return $this->__soapCall('GetAllergyXML', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Return dataset of conditions/diseases and matching condition ids and  icd9 codes for given 
   * search term (search can be conditional name/desc or an ICD9 code/partial code) 
   *
   * @param GetConditionDS $parameters
   * @return GetConditionDSResponse
   */
  public function GetConditionDS(GetConditionDS $parameters) {
    return $this->__soapCall('GetConditionDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WS Partners: Return dataset of new/unanswered Pharmacy Refill Requests to display to users. 
   * 
   *
   * @param GetRefillRequestsDS $parameters
   * @return GetRefillRequestsDSResponse
   */
  public function GetRefillRequestsDS(GetRefillRequestsDS $parameters) {
    return $this->__soapCall('GetRefillRequestsDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Return dataset of Pharmacy Refill Requests per date and status (answered/not answered). 
   * 
   *
   * @param GetRefillRequestsAllDS $parameters
   * @return GetRefillRequestsAllDSResponse
   */
  public function GetRefillRequestsAllDS(GetRefillRequestsAllDS $parameters) {
    return $this->__soapCall('GetRefillRequestsAllDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Return object array of Pharmacy Refill Requests per date and status (answered/not answered). 
   * 
   *
   * @param GetRefillRequestsAllObj $parameters
   * @return GetRefillRequestsAllObjResponse
   */
  public function GetRefillRequestsAllObj(GetRefillRequestsAllObj $parameters) {
    return $this->__soapCall('GetRefillRequestsAllObj', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Return a count of new/unanswered Pharmacy Refill Requests to display to users. 
   *
   * @param GetRefillRequestsCount $parameters
   * @return GetRefillRequestsCountResponse
   */
  public function GetRefillRequestsCount(GetRefillRequestsCount $parameters) {
    return $this->__soapCall('GetRefillRequestsCount', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WS Partners: Return dataset with refill details including XML of requested refill 
   *
   * @param GetRefillRequestDetailDS $parameters
   * @return GetRefillRequestDetailDSResponse
   */
  public function GetRefillRequestDetailDS(GetRefillRequestDetailDS $parameters) {
    return $this->__soapCall('GetRefillRequestDetailDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WS Partners: Return dataset of Refill Response Denial Reason Codes for responding to refill 
   * requests from your screens. 
   *
   * @param GetRefillResponseDenialCodesDS $parameters
   * @return GetRefillResponseDenialCodesDSResponse
   */
  public function GetRefillResponseDenialCodesDS(GetRefillResponseDenialCodesDS $parameters) {
    return $this->__soapCall('GetRefillResponseDenialCodesDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WS Partners:Return dataset of Patient Prescription Benefit Eligibility - For Web Service 
   * Backend Partners.  Pass in Patient to check and prescriber who is doing the check.  Note: 
   * Eligibility can only be checked once per Patient Encounter and not more than 3 days before 
   * the encounter. 
   *
   * @param GetPatientEligibilityDS $parameters
   * @return GetPatientEligibilityDSResponse
   */
  public function GetPatientEligibilityDS(GetPatientEligibilityDS $parameters) {
    return $this->__soapCall('GetPatientEligibilityDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WS Partners:Return xml string of Patient Prescription Benefit Eligibility - For Web Service 
   * Backend Partners.  Pass in Patient to check and prescriber who is doing the check.  Note: 
   * Eligibility can only be checked once per Patient Encounter and not more than 3 days before 
   * the encounter. 
   *
   * @param GetPatientEligibilityXML $parameters
   * @return GetPatientEligibilityXMLResponse
   */
  public function GetPatientEligibilityXML(GetPatientEligibilityXML $parameters) {
    return $this->__soapCall('GetPatientEligibilityXML', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Return dataset of Patient Prescription Benefit Eligibility on file from any previous Eligibility 
   * Checks (within 3 days).This method will not send a new Eligibility Request to the PBMs, 
   * it will only return any valid eligibility data from previous checks (data is only good 
   * for 3 days). 
   *
   * @param GetPatientEligibilityOnFileDS $parameters
   * @return GetPatientEligibilityOnFileDSResponse
   */
  public function GetPatientEligibilityOnFileDS(GetPatientEligibilityOnFileDS $parameters) {
    return $this->__soapCall('GetPatientEligibilityOnFileDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Return XML String of Patient Prescription Benefit Eligibility on file from any previous 
   * Eligibility Checks (within 3 days).This method will not send a new Eligibility Request 
   * to the PBMs, it will only return any valid eligibility data from previous checks (data 
   * is only good for 3 days). 
   *
   * @param GetPatientEligibilityOnFileXML $parameters
   * @return GetPatientEligibilityOnFileXMLResponse
   */
  public function GetPatientEligibilityOnFileXML(GetPatientEligibilityOnFileXML $parameters) {
    return $this->__soapCall('GetPatientEligibilityOnFileXML', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WSPartners:Return dataset of Patient RX History per passed in Eligibity or Pharmacy info. 
   *  Pass date range (optional) to get history for that date otherwise will return all hx 
   * avail.  WhichElig param should be 0=All Types (Fill & PBMs), 1=Pharmacy fill data only, 
   * or specific #### for just one elig id to use.  This data comes from Pharmacies and/or 
   * PBM payers per which elig parameter. 
   *
   * @param GetPatientRxHistoryRequestDS $parameters
   * @return GetPatientRxHistoryRequestDSResponse
   */
  public function GetPatientRxHistoryRequestDS(GetPatientRxHistoryRequestDS $parameters) {
    return $this->__soapCall('GetPatientRxHistoryRequestDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WSPartners: Check Provider For Surescripts Services:  Per given SPI (unique prescriber-location-id) 
   * 
   *
   * @param CheckPrescriberRegistration $parameters
   * @return CheckPrescriberRegistrationResponse
   */
  public function CheckPrescriberRegistration(CheckPrescriberRegistration $parameters) {
    return $this->__soapCall('CheckPrescriberRegistration', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WSPartners: Check Provider For Surescripts Services: Pass in unique id for prescriber 
   * and location 
   *
   * @param CheckPrescriberRegistrationPerId $parameters
   * @return CheckPrescriberRegistrationPerIdResponse
   */
  public function CheckPrescriberRegistrationPerId(CheckPrescriberRegistrationPerId $parameters) {
    return $this->__soapCall('CheckPrescriberRegistrationPerId', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WSPartners: Register a Doctor for Surescripts Services (not needed if using screens or 
   * using auto registration) 
   *
   * @param RegisterPrescriber $parameters
   * @return RegisterPrescriberResponse
   */
  public function RegisterPrescriber(RegisterPrescriber $parameters) {
    return $this->__soapCall('RegisterPrescriber', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WSPartners:Electronical Send a Response to Pharmacy for a received Refill Request (for 
   * Web Service Back end partners only). Pass the RefillRequestId of the Request being responded 
   * to (as received from GetRefillRequestsDS Method call) 
   *
   * @param eSendRefillReqResponse $parameters
   * @return eSendRefillReqResponseResponse
   */
  public function eSendRefillReqResponse(eSendRefillReqResponse $parameters) {
    return $this->__soapCall('eSendRefillReqResponse', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Returns count of esend and refill request reponse message alerts (errors) that have occurred 
   * since the last request for alerts 
   *
   * @param GetNewMessageAlertsCount $parameters
   * @return GetNewMessageAlertsCountResponse
   */
  public function GetNewMessageAlertsCount(GetNewMessageAlertsCount $parameters) {
    return $this->__soapCall('GetNewMessageAlertsCount', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Returns an xml dataset of esend and refill request reponse message alerts (errors) that 
   * have occurred since the last request for alerts  Method will return alerts on file and 
   * then mark all alerts as complete/recieved so next call will not get the same alert. 
   *
   * @param GetNewMessageAlerts $parameters
   * @return GetNewMessageAlertsResponse
   */
  public function GetNewMessageAlerts(GetNewMessageAlerts $parameters) {
    return $this->__soapCall('GetNewMessageAlerts', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Update the given error alert as complete/received 
   *
   * @param UpdateErrorMessageAlertComplete $parameters
   * @return UpdateErrorMessageAlertCompleteResponse
   */
  public function UpdateErrorMessageAlertComplete(UpdateErrorMessageAlertComplete $parameters) {
    return $this->__soapCall('UpdateErrorMessageAlertComplete', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Returns an xml dataset of esend and refill request reponse message alerts (errors) that 
   * have not been marked completed/recieved yet 
   *
   * @param GetErrorMessageAlertsDS $parameters
   * @return GetErrorMessageAlertsDSResponse
   */
  public function GetErrorMessageAlertsDS(GetErrorMessageAlertsDS $parameters) {
    return $this->__soapCall('GetErrorMessageAlertsDS', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Returns number of esend and refill request reponse message alerts (errors) that have not 
   * been marked completed/recieved yet 
   *
   * @param GetErrorMessageAlertsCount $parameters
   * @return GetErrorMessageAlertsCountResponse
   */
  public function GetErrorMessageAlertsCount(GetErrorMessageAlertsCount $parameters) {
    return $this->__soapCall('GetErrorMessageAlertsCount', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Check the status of a e-send newrx message that was previously sent to a Pharmacy 
   *
   * @param GeteSendMessageStatus $parameters
   * @return GeteSendMessageStatusResponse
   */
  public function GeteSendMessageStatus(GeteSendMessageStatus $parameters) {
    return $this->__soapCall('GeteSendMessageStatus', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Check the status of a e-send refill response message that was previously sent to a Pharmacy 
   * 
   *
   * @param GeteSendRefillMessageStatus $parameters
   * @return GeteSendRefillMessageStatusResponse
   */
  public function GeteSendRefillMessageStatus(GeteSendRefillMessageStatus $parameters) {
    return $this->__soapCall('GeteSendRefillMessageStatus', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WSPartners:Electronically Send/Route a Prescription to a Pharmacy.  For use from WebService 
   * Backend Integration Partners (not available to screen integration partners who use MDToolbox 
   * screens to write and send rxs.) 
   *
   * @param eSendNewRx $parameters
   * @return eSendNewRxResponse
   */
  public function eSendNewRx(eSendNewRx $parameters) {
    return $this->__soapCall('eSendNewRx', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * WS Partners: For back end integration partners (not using MDToolbox screens) - If using 
   * PBM formulary information both eSends and Print numbers must be reported to the PBMs for 
   * statistics. Use this routine each time a prescription is manually printed out of your 
   * system so the statistics are correct. 
   *
   * @param UpdatePrinted $parameters
   * @return UpdatePrintedResponse
   */
  public function UpdatePrinted(UpdatePrinted $parameters) {
    return $this->__soapCall('UpdatePrinted', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * ScreenSharing: Upload data for screen sharing.  Returns authentication key to be able 
   * to open the MDToolbox screens bypassing login. 
   *
   * @param UpdateDataForScreens $parameters
   * @return UpdateDataForScreensResponse
   */
  public function UpdateDataForScreens(UpdateDataForScreens $parameters) {
    return $this->__soapCall('UpdateDataForScreens', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * ScreenSharing:Return dataset of Patients Prescriptions on file at MDTB-RX (used when doing 
   * screen integration-for keeping rx local copy in sync) 
   *
   * @param GetPrescriptionsForPatient $parameters
   * @return GetPrescriptionsForPatientResponse
   */
  public function GetPrescriptionsForPatient(GetPrescriptionsForPatient $parameters) {
    return $this->__soapCall('GetPrescriptionsForPatient', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Return string formatted XML of Patients Prescriptions on file at MDTB-RX (used when doing 
   * screen integration-for keeping rx local copy in sync) 
   *
   * @param GetPrescriptionsForPatientXML $parameters
   * @return GetPrescriptionsForPatientXMLResponse
   */
  public function GetPrescriptionsForPatientXML(GetPrescriptionsForPatientXML $parameters) {
    return $this->__soapCall('GetPrescriptionsForPatientXML', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Return array of Medication Record Object - Patients Prescriptions on file at MDTB-RX (used 
   * when doing screen integration-for keeping rx local copy in sync) 
   *
   * @param GetPrescriptionsForPatientObj $parameters
   * @return GetPrescriptionsForPatientObjResponse
   */
  public function GetPrescriptionsForPatientObj(GetPrescriptionsForPatientObj $parameters) {
    return $this->__soapCall('GetPrescriptionsForPatientObj', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * ScreenSharing:Return dataset of Patients Allergies on file at MDTB-RX 
   *
   * @param GetAllergiesForPatient $parameters
   * @return GetAllergiesForPatientResponse
   */
  public function GetAllergiesForPatient(GetAllergiesForPatient $parameters) {
    return $this->__soapCall('GetAllergiesForPatient', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * ScreenSharing:Return object array of Patients Allergies on file at MDTB-RX 
   *
   * @param GetAllergiesForPatientObj $parameters
   * @return GetAllergiesForPatientObjResponse
   */
  public function GetAllergiesForPatientObj(GetAllergiesForPatientObj $parameters) {
    return $this->__soapCall('GetAllergiesForPatientObj', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * Get a list of allowed suffix/credentials that can be sent for prescriber 
   *
   * @param GetPrescriberAllowedSuffixStr $parameters
   * @return GetPrescriberAllowedSuffixStrResponse
   */
  public function GetPrescriberAllowedSuffixStr(GetPrescriberAllowedSuffixStr $parameters) {
    return $this->__soapCall('GetPrescriberAllowedSuffixStr', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * ScreenSharing:Return dataset of Prescriptions on file at MDTB-RX for a particular prescriber 
   * and date range (used when doing screen integration-for keeping rx local copy in sync) 
   * 
   *
   * @param GetPrescriptionsByPrescriber $parameters
   * @return GetPrescriptionsByPrescriberResponse
   */
  public function GetPrescriptionsByPrescriber(GetPrescriptionsByPrescriber $parameters) {
    return $this->__soapCall('GetPrescriptionsByPrescriber', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

  /**
   * ScreenSharing: Upload patient vital history for your patient. 
   *
   * @param UpdateDataPatientVitals $parameters
   * @return UpdateDataPatientVitalsResponse
   */
  public function UpdateDataPatientVitals(UpdateDataPatientVitals $parameters) {
    return $this->__soapCall('UpdateDataPatientVitals', array($parameters),       array(
            'uri' => 'http://mdtoolboxrx.com/',
            'soapaction' => ''
           )
      );
  }

}

?>
