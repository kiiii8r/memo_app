$(function() {
    $('#delete').click(function() {
        if(confirm('本当に削除していいですか？')) {
            return true;
        }else{
            alert('キャンセルしました');
            return false;
        }
    });

    $('#image-confirm').click(function() {
        if(confirm('プロフィール画像を変更してもよろしいですか？')) {
            return true;
        }else{
            alert('キャンセルしました');
            return false;
        }
    });

    $('#profile-confirm').click(function() {
        if(confirm('プロフィールを更新してもよろしいですか？')) {
            return true;
        }else{
            alert('キャンセルしました');
            return false;
        }
    });
});
