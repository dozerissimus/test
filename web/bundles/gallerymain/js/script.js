$(document).ready(function(){
    
    $('#fos_user_profile_form_for_update').val('1');

/*--------------------------------------------------------------------*/
    $('#left-menu p:first').addClass('active');
    $('#left-menu ul').hide();

    $('#left-menu p').click(function(){
    	$(this).next('ul').slideToggle('slow')
            .siblings('ul:visible').slideUp('slow');
        $(this).toggleClass('active');
        $(this).siblings('p').removeClass('active');
    });	
	
/*--------------------------------------------------------------------*/

    function uploadAvatar()
    {
        var btnUpload=$('#avatarUpload');
        var status=$('#status');
	new AjaxUpload(btnUpload, {
            action: '/app_dev.php/ajax/uploadimage',
            name: 'file',
                        
            onSubmit: function(file, ext){
		if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext)))
		{  
                    status.text('Поддерживаемые форматы JPG, PNG или GIF');
                    return false;
		}
		status.text('Загрузка...');
            },
            onComplete: function(file, response)
            {		
                status.text('');
		if(response === 'error')
		{
                    status.text('Файл не загружен: ' + file);
		} 
		else
		{
                    var avatar = JSON.parse(response);		
                    $('#avatar').attr('src', avatar.temp_uri);
                    $('#fos_user_profile_form_temp_md5').val(avatar.temp_md5);
                    $('#fos_user_profile_form_temp_name').val(avatar.temp_name);

		}
            }
	});
    }   
    //uploadAvatar();
    
    function uploadImage()
    {
        var btnUpload=$('#imageUpload');
        var status=$('#status');
	new AjaxUpload(btnUpload, {
            action: '/app_dev.php/ajax/uploadimage',
            name: 'file',
                        
            onSubmit: function(file, ext){
		if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext)))
		{  
                    status.text('Поддерживаемые форматы JPG, PNG или GIF');
                    return false;
		}
		status.text('Загрузка...');
            },
            onComplete: function(file, response)
            {		
                status.text('');
		if(response === 'error')
		{
                    status.text('Файл не загружен: ' + file);
		} 
		else
		{
                    var image = JSON.parse(response);		
                    $('.preview').attr('src', image.temp_uri);
                    $('.preview').attr('hidden', false);
                    $('#gallery_mainbundle_image_temp_md5').val(image.temp_md5);
                    $('#gallery_mainbundle_image_temp_name').val(image.temp_name);

		}
            }
	});
    }
    //uploadImage();

	
    $('#avatarUpload, #imageUpload').click(function(){
	uploadAvatar();
    });
    
        
    /*$('.postimage').mouseover(function(){
        $('.nav-arrow-left').css('opacity', '0.5');
        $('.nav-arrow-right').css('opacity', '0.5');
    });  
    
    /*$('.postimage').mouseout(function(){
        $('.nav-arrow-left').css('opacity', '0');
        $('.nav-arrow-right').css('opacity', '0');
    });*/
    
    function ajaxGetPage(op)
    {
        var cat_id = $('#cat_id').text();
        var page = $('#page').text();
        $('.loading-sprite').css('display', 'block');
        //$('.loading-sprite').css('filter', 'blur(0)');
        //$('.postimage').css('filter', 'blur(2px)');
        $.ajax({
            url: '/app_dev.php/ajax/getpage',
            type: 'POST',
            dataType: 'json',
            data: {cat_id: cat_id, page: page, op: op},
            success: function(data){					
                $('.loading-sprite').css('display', 'none');
                //$('.postimage').css('filter', 'blur(0)');
                $('#im-description').text(data.image.description);
                $('#im-posted').text(data.image.autor);
                $('#im-date').text(data.image.date.date);
                $('#page').text(data.image.page); 
                $('#idim').text(data.image.idim);
                $('.postimage').css('background-image', 'url("/uploads/images/'+data.image.filename+'")');
                $('.comment-wrapper').html(data.image.comments);
            },
            failure: function(){
                
            }
        });
    }
   
    $('.nav-arrow-left').click(function(){
        ajaxGetPage('dec');
    });
    
    $('.nav-arrow-right, .nav-image-next').click(function(){
        ajaxGetPage('inc');
    });
    /*$('.postimage').click(function(){
        ajaxGetPage('inc');
    });*/

    $('.send-comment').click(function(){
        var idim = $('#idim').text();
        var comment = $('#comment-body').val();
        
        $.ajax({
            url: '/app_dev.php/comment/create',
            type: 'POST',
            dataType: 'json',
            data: {comment: comment, idim: idim},
            success: function(data){
                if (data)
                {
                    $('.comment-wrapper').html(data.comments);   
                    $('#comment-body').val('');
                }
            },
            failure: function(){}
        });
    });

});	
$(window).load(function(){
	
});






