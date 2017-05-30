{
  Export list of CELLs
}
unit UserScript;

var
  sl: TStringList;

function Initialize: integer;
begin
  sl := TStringList.Create;
end;

function Process(e: IInterface): integer;
var
  effects, effect, kwda: IInterface;
  i: integer;
  s: string;
  exists: boolean;
begin
  if Signature(e) <> 'ALCH' then
    Exit;	

  AddMessage('Processing: ' +  IntToHex(FixedFormID(e), 8) + '; ' + GetElementEditValues(e, 'Full - Name'));
	
  kwda := ElementBySignature(e, 'KWDA');
  
  if not Assigned(kwda) then begin
    AddMessage('No keywords subrecord in ' + Name(e));    
    Exit;
  end;
  
  exists := false;  
  for i := 0 to ElementCount(kwda) - 1 do begin
	if IntToHex(GetNativeValue(ElementByIndex(kwda, i)), 8) = '0008CDEC' then begin //VendorItemPotion
        exists := true;
        Break;	
	end;
  end;
  
  if not exists then begin
    AddMessage('Not a VendorItemPotion');    
	Exit;
  end;	
	
  //AddMessage(GetElementEditValues(e, 'Full - Name'));
  //AddMessage(GetElementEditValues(e, 'DATA - Weight'));
  //AddMessage(GetElementEditValues(e, 'ENIT\Value'));
  
  s :=
    IntToHex(FixedFormID(e), 8) + ';' +
	GetElementEditValues(e, 'Full - Name') + ';' +
	GetElementEditValues(e, 'DATA - Weight') + ';' +
	GetElementEditValues(e, 'ENIT\Value');
  
  effects := ElementByName(e, 'Effects');
  for i := 0 to Pred(ElementCount(effects)) do begin
    effect := ElementByIndex(effects, i);
	
	s := s + ';' +
	  GetElementEditValues(effect, 'EFID - Base Effect') + ';' +
	  GetElementEditValues(effect, 'EFIT\Magnitude') + ';' +
	  GetElementEditValues(effect, 'EFIT\Duration');
  end;

  //AddMessage(s);  
  sl.Add(s);
end;

function Finalize: integer;
var
  fname: string;
begin
  if sl.Count = 0 then 
    Exit;
	
  fname := ProgramPath + 'potions.txt';
  AddMessage('Saving list to ' + fname);
  sl.SaveToFile(fname);
  sl.Free;
end;
end.
