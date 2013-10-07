<?php
use_helper('Javascript', 'opUtil', 'opAsset');
op_smt_use_javascript('/opCommunityTopicPlugin/js/moment.min.js', 'last');
op_smt_use_javascript('/opCommunityTopicPlugin/js/lang/ja.js', 'last');
?>
<script id="topicEntry" type="text/x-jquery-tmpl">
<div class="row">
  <div class="span3">${$item.calcTimeAgo()}</div>
  <div class="span9"><a href="<?php echo public_path('communityTopic')?>/${id}">${name}</a> (${community_name})</div>
</div>
</script>

<script type="text/javascript">
$(function(){
  var params = {
    apiKey: openpne.apiKey,
    format: 'mini',
    target: 'member',
    target_id: <?php echo $memberId ?>,
    count: 4
  }

  $.getJSON(openpne.apiBase + 'topic/search.json',
    params,
    function(res)
    {
      if (res.data.length > 0)
      {
        var entry = $('#topicEntry').tmpl(res.data,
        {
          calcTimeAgo: function(){
            return moment(this.data.updated_at, 'YYYY-MM-DD HH:mm:ss').fromNow();
          }
        });
        $('#topicList').append(entry);
        $('#readmore').show();
      }
    }
  )
})
</script>

<hr class="toumei" />
<div class="row">
  <div class="gadget_header span12">トピック一覧</div>
</div>
<hr class="toumei" />
<div id="topicList" style="margin-left: 0px;">
</div>

<div class="row hide" id="readmore">
<?php if ($communityId)  { ?>
<a href="<?php echo public_path('communityTopic/listCommunity').'/'.$communityId ?>" class="btn btn-block span11"><?php echo __('More')?></a>
<?php } ?>
</div>
