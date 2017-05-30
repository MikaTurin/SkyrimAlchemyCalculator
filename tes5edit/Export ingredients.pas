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
  end;
end;

function Finalize: integer;
var
  fname: string;
begin
  fname := ProgramPath + 'ingredients.txt';
  AddMessage('Saving list to ' + fname);
  sl.SaveToFile(fname);
  sl.Free;
end;

end.
