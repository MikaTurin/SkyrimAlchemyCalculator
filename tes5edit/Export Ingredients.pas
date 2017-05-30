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
  effects, effect: IInterface;
  i: integer;
  entry: string;
begin
  if Signature(e) <> 'INGR' then
    Exit;	

  AddMessage('Processing: ' +  IntToHex(FixedFormID(e), 8) + '; ' + GetElementEditValues(e, 'Full - Name'));
  
  effects := ElementByName(e, 'Effects');
  for i := 0 to Pred(ElementCount(effects)) do begin
    effect := ElementByIndex(effects, i);
	//AddMessage('Processing: ' +  GetElementEditValues(effect, 'EFID - Base Effect'));
	//AddMessage(GetElementEditValues(effect, 'EFIT\Magnitude'));
	//AddMessage(GetElementEditValues(effect, 'EFIT\Duration'));
	
	sl.Add(
		IntToHex(FixedFormID(e), 8) + ';' +
		GetElementEditValues(e, 'Full - Name') + ';' +
		GetElementEditValues(effect, 'EFID - Base Effect') + ';' +
		GetElementEditValues(effect, 'EFIT\Magnitude') + ';' +
		GetElementEditValues(effect, 'EFIT\Duration') + ';' +
		GetElementEditValues(e, 'DATA\Value')
	);
	
    //if GetElementEditValues(effect, 'PRKE\Type') <> 'Entry Point' then
      //Continue;
    //entry := GetElementEditValues(effect, 'DATA\Entry Point\Entry Point');
    //sl.Values[entry] := sl.Values[entry] + #13#10 + Name(e);
  end;

	
  //sl.Add(IntToHex(FixedFormID(e), 8) + ';' + GetElementEditValues(e, 'EDID'));
end;

function Finalize: integer;
var
  fname: string;
begin
  fname := ProgramPath + 'Edit Scripts\ingredients.txt';
  AddMessage('Saving list to ' + fname);
  sl.SaveToFile(fname);
  sl.Free;
end;

end.
