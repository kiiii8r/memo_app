$(function() {
    $('#delete').click(function() {
        if(confirm('本当に削除していいですか？')) {
            return true;
        }else{
            alert('キャンセルしました');
            return false;
        }
    });
});
