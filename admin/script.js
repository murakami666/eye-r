function regCheck(f){
	if(confirm("登録しますか？")){
	  return true;
	}else{
		return false;
	}
}
function updCheck(f){
	if(confirm("更新しますか？")){
	  return true;
	}else{
		return false;
	}
}
function delCheck(f){
	if(confirm("削除しますか？")){
	  return true;
	}else{
		return false;
	}
}
function sendCheck(f){
	if(confirm("送信しますか？")){
	  return true;
	}else{
		return false;
	}
}
function pg_ch(i){
	document.page_form.page.value = i;
	document.page_form.submit();
}
