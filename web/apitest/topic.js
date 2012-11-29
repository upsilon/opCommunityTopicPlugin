function runTests(apiBase, apiKey) {
  QUnit.moduleStart(function(details) {
    $.ajax(apiBase + 'test/setup.json?force=1&target=opCommunityTopicPlugin', { async: false });
  });

  module('topic/search.json');

  asyncTest('should return topics', 5, function() {
    $.getJSON(apiBase + 'topic/search.json',
    {
      apiKey: apiKey['me'],
      target: 'community',
      target_id: 1,
      format: 'mini'
    },
    function(data, textStatus, jqXHR){
      equal(jqXHR.status, 200);
      equal(data.status, 'success', 'should return status code "success"');

      equal(data.data.length, 15, 'should return 15 topics');
      ok(data.data[1], 'topic 1 should have latest comment ');
      equal(data.data[1].latest_comment, 'トピック a 10', 'latest comment of topic 1 should have body "トピック a 10"');

      start();
    });
  });

  asyncTest('should be able to limit numbers of topic by a parameter "count"', 3, function() {
    $.getJSON(apiBase + 'topic/search.json',
    {
      apiKey: apiKey['me'],
      target: 'community',
      target_id: 1,
      format: 'mini',
      count: 5
    },
    function(data, textStatus, jqXHR){
      equal(jqXHR.status, 200);
      equal(data.status, 'success', 'should return status code "success"');

      equal(data.data.length, 5, 'should return 5 topics');

      start();
    });
  });
}

runTests(
  '../../api.php/',
  {
    'me': 'dummyApiKey', // member1
  }
);
