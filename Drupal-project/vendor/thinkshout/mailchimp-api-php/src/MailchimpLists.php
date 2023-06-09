<?php

namespace Mailchimp;

/**
 * Mailchimp Lists library.
 *
 * @package Mailchimp
 */
class MailchimpLists extends MailchimpApiUser {

  const MEMBER_STATUS_SUBSCRIBED = 'subscribed';
  const MEMBER_STATUS_UNSUBSCRIBED = 'unsubscribed';
  const MEMBER_STATUS_CLEANED = 'cleaned';
  const MEMBER_STATUS_PENDING = 'pending';

  /**
   * Gets information about all lists owned by the authenticated account.
   *
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/#read-get_lists
   */
  public function getLists($parameters = []) {
    return $this->api_class->request('GET', '/lists', NULL, $parameters);
  }

  /**
   * Gets a Mailchimp list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/#read-get_lists_list_id
   */
  public function getList($list_id, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
    ];

    return $this->api_class->request('GET', '/lists/{list_id}', $tokens, $parameters);
  }

  /**
   * Gets information about all interest categories associated with a list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/interest-categories/#read-get_lists_list_id_interest_categories
   */
  public function getInterestCategories($list_id, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
    ];

    return $this->api_class->request('GET', '/lists/{list_id}/interest-categories', $tokens, $parameters);
  }

  /**
   * Create new interest category associated with a list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $title
   *   The title of interest category.
   * @param string $type
   *   The type of interest category.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see https://developer.mailchimp.com/documentation/mailchimp/reference/lists/interest-categories/#create-post_lists_list_id_interest_categories
   */
  public function addInterestCategories($list_id, $title, $type, $parameters = [], $batch = FALSE) {
    $tokens = [
      'list_id' => $list_id,
    ];

    $parameters += [
      'title' => $title,
      'type' => $type,
    ];

    return $this->api_class->request('POST', '/lists/{list_id}/interest-categories', $tokens, $parameters);
  }

  /**
   * Update interest category associated with a list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $interest_category_id
   *   The ID of the interest category.
   * @param string $title
   *   The title of interest category.
   * @param string $type
   *   The type of interest category.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see https://developer.mailchimp.com/documentation/mailchimp/reference/lists/interest-categories/#create-post_lists_list_id_interest_categories
   */
  public function updateInterestCategories($list_id, $interest_category_id, $title, $type, $parameters = [], $batch = FALSE) {
    $tokens = [
      'list_id' => $list_id,
      'interest_category_id' => $interest_category_id,
    ];

    $parameters += [
      'title' => $title,
      'type' => $type,
    ];

    return $this->api_class->request('PATCH', '/lists/{list_id}/interest-categories/{interest_category_id}', $tokens, $parameters);
  }

  /**
   * Update interest category associated with a list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $interest_category_id
   *   The ID of the interest category.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see https://developer.mailchimp.com/documentation/mailchimp/reference/lists/interest-categories/#create-post_lists_list_id_interest_categories
   */
  public function deleteInterestCategories($list_id, $interest_category_id, $parameters = [], $batch = FALSE) {
    $tokens = [
      'list_id' => $list_id,
      'interest_category_id' => $interest_category_id,
    ];

    return $this->api_class->request('DELETE', '/lists/{list_id}/interest-categories/{interest_category_id}', $tokens, $parameters);
  }

  /**
   * Gets information about all interests associated with an interest category.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $interest_category_id
   *   The ID of the interest category.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/interest-categories/interests/#read-get_lists_list_id_interest_categories_interest_category_id_interests
   */
  public function getInterests($list_id, $interest_category_id, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
      'interest_category_id' => $interest_category_id,
    ];

    return $this->api_class->request('GET', '/lists/{list_id}/interest-categories/{interest_category_id}/interests', $tokens, $parameters);
  }

  /**
   * Create new interest in category.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $interest_category_id
   *   The ID of interest category.
   * @param string $name
   *   The name of interest.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see https://mailchimp.com/developer/reference/lists/interest-categories/interests/
   */
  public function addInterests($list_id, $interest_category_id, $name, $parameters = [], $batch = FALSE) {
    $tokens = [
      'list_id' => $list_id,
      'interest_category_id' => $interest_category_id,
    ];

    $parameters += [
      'name' => $name,
    ];

    return $this->api_class->request('POST', '/lists/{list_id}/interest-categories/{interest_category_id}/interests', $tokens, $parameters);
  }

  /**
   * Edit interest in category.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $interest_category_id
   *   The ID of interest category.
   * @param string $interest_id
   *   The ID of interest.
   * @param string $name
   *   The name of interest.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see https://developer.mailchimp.com/documentation/mailchimp/reference/lists/interest-categories/#create-post_lists_list_id_interest_categories
   */
  public function updateInterests($list_id, $interest_category_id, $interest_id, $name, $parameters = [], $batch = FALSE) {
    $tokens = [
      'list_id' => $list_id,
      'interest_category_id' => $interest_category_id,
      'interest_id' => $interest_id,
    ];

    $parameters += [
      'name' => $name,
    ];

    return $this->api_class->request('PATCH', '/lists/{list_id}/interest-categories/{interest_category_id}/interests/{interest_id}', $tokens, $parameters);
  }

  /**
   * Delete interest in category.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $interest_category_id
   *   The ID of interest category.
   * @param string $interest_id
   *   The ID of interest.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see https://developer.mailchimp.com/documentation/mailchimp/reference/lists/interest-categories/#create-post_lists_list_id_interest_categories
   */
  public function deleteInterests($list_id, $interest_category_id, $interest_id, $parameters = [], $batch = FALSE) {
    $tokens = [
      'list_id' => $list_id,
      'interest_category_id' => $interest_category_id,
      'interest_id' => $interest_id,
    ];

    return $this->api_class->request('DELETE', '/lists/{list_id}/interest-categories/{interest_category_id}/interests/{interest_id}', $tokens, $parameters);
  }

  /**
   * Gets merge fields associated with a Mailchimp list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/merge-fields/#read-get_lists_list_id_merge_fields
   */
  public function getMergeFields($list_id, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
    ];

    return $this->api_class->request('GET', '/lists/{list_id}/merge-fields', $tokens, $parameters);
  }

  /**
   * Add merge field associated with a Mailchimp list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $name
   *   The name of the merge field.
   * @param string $type
   *   The type for the merge field.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/merge-fields/#create-post_lists_list_id_merge_fields
   */
  public function addMergeField($list_id, $name, $type, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
    ];

    $parameters += [
      'name' => $name,
      'type' => $type,
    ];

    return $this->api_class->request('POST', '/lists/{list_id}/merge-fields', $tokens, $parameters);
  }

  /**
   * Gets information about all members of a Mailchimp list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/#read-get_lists_list_id_members
   */
  public function getMembers($list_id, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
    ];

    return $this->api_class->request('GET', '/lists/{list_id}/members', $tokens, $parameters);
  }

  /**
   * Gets information about a member of a Mailchimp list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $email
   *   The member's email address.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/#read-get_lists_list_id_members_subscriber_hash
   */
  public function getMemberInfo($list_id, $email, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
      'subscriber_hash' => md5(strtolower($email)),
    ];

    return $this->api_class->request('GET', '/lists/{list_id}/members/{subscriber_hash}', $tokens, $parameters);
  }

  /**
   * Add an event for a Mailchimp list member.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $email
   *   The member's email address.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see https://mailchimp.com/developer/marketing/api/list-member-events/add-event/
   */
  public function addMemberEvent($list_id, $email, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
      'subscriber_hash' => md5(strtolower($email)),
    ];

    return $this->api_class->request('POST', '/lists/{list_id}/members/{subscriber_hash}/events', $tokens, $parameters);
  }

  /**
   * Gets information about a member of a Mailchimp list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $mc_eid
   *   The member's unique ID.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see https://developer.mailchimp.com/documentation/mailchimp/guides/getting-started-with-ecommerce/
   */
  public function getMemberInfoById($list_id, $mc_eid, $parameters = []) {
    $tokens = [
        'list_id' => $list_id,
    ];

    $parameters = [
        'unique_email_id' => $mc_eid,
    ];

    return $this->api_class->request('GET', '/lists/{list_id}/members/', $tokens, $parameters);
  }

  /**
   * Gets activity related to a member of a Mailchimp list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $email
   *   The member's email address.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/activity/#read-get_lists_list_id_members_subscriber_hash_activity
   */
  public function getMemberActivity($list_id, $email, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
      'subscriber_hash' => md5(strtolower($email)),
    ];

    return $this->api_class->request('GET', '/lists/{list_id}/members/{subscriber_hash}/activity', $tokens, $parameters);
  }

  /**
   * Get events for a Mailchimp contact.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $email
   *   The member's email address.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see https://mailchimp.com/developer/marketing/api/list-member-events/list-member-events/
   */
  public function getMemberEvents($list_id, $email, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
      'subscriber_hash' => md5(strtolower($email)),
    ];

    return $this->api_class->request('GET', '/lists/{list_id}/members/{subscriber_hash}/events', $tokens, $parameters);
  }

  /**
   * Adds a new member to a Mailchimp list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $email
   *   The email address to add.
   * @param array $parameters
   *   Associative array of optional request parameters.
   * @param bool $batch
   *   TRUE to create a new pending batch operation.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/#create-post_lists_list_id_members
   */
  public function addMember($list_id, $email, $parameters = [], $batch = FALSE) {
    $tokens = [
      'list_id' => $list_id,
    ];

    $parameters += [
      'email_address' => $email,
    ];

    return $this->api_class->request('POST', '/lists/{list_id}/members', $tokens, $parameters, $batch);
  }

  /**
   * Removes a member from a Mailchimp list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $email
   *   The member's email address.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/#delete-delete_lists_list_id_members_subscriber_hash
   */
  public function removeMember($list_id, $email) {
    $tokens = [
      'list_id' => $list_id,
      'subscriber_hash' => md5(strtolower($email)),
    ];

    return $this->api_class->request('DELETE', '/lists/{list_id}/members/{subscriber_hash}', $tokens);
  }

  /**
   * Updates a member of a Mailchimp list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $email
   *   The member's email address.
   * @param array $parameters
   *   Associative array of optional request parameters.
   * @param bool $batch
   *   TRUE to create a new pending batch operation.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/#edit-patch_lists_list_id_members_subscriber_hash
   */
  public function updateMember($list_id, $email, $parameters = [], $batch = FALSE) {
    $tokens = [
      'list_id' => $list_id,
      'subscriber_hash' => md5(strtolower($email)),
    ];

    return $this->api_class->request('PATCH', '/lists/{list_id}/members/{subscriber_hash}', $tokens, $parameters, $batch);
  }

  /**
   * Adds a new or update an existing member of a Mailchimp list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $email
   *   The member's email address.
   * @param array $parameters
   *   Associative array of optional request parameters.
   * @param bool $batch
   *   TRUE to create a new pending batch operation.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/#edit-put_lists_list_id_members_subscriber_hash
   */
  public function addOrUpdateMember($list_id, $email, $parameters = [], $batch = FALSE) {
    $tokens = [
      'list_id' => $list_id,
      'subscriber_hash' => md5(strtolower($email)),
    ];

    $parameters += [
      'email_address' => $email,
    ];

    return $this->api_class->request('PUT', '/lists/{list_id}/members/{subscriber_hash}', $tokens, $parameters, $batch);
  }

  /**
   * Gets tags related to a member of a MailChimp list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $email
   *   The member's email address.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/tags/#read-get_lists_list_id_members_subscriber_hash_tags
   */
  public function getMemberTags($list_id, $email, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
      'subscriber_hash' => md5(strtolower($email)),
    ];

    return $this->api_class->request('GET', '/lists/{list_id}/members/{subscriber_hash}/tags', $tokens, $parameters);
  }

  /**
   * Gets information about segments associated with a Mailchimp list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/segments/#read-get_lists_list_id_segments
   */
  public function getSegments($list_id, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
    ];

    return $this->api_class->request('GET', '/lists/{list_id}/segments', $tokens, $parameters);
  }

  /**
   * Gets a Mailchimp list segment.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $segment_id
   *   The ID of the list segment.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/segments/#read-get_lists_list_id_segments_segment_id
   */
  public function getSegment($list_id, $segment_id, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
      'segment_id' => $segment_id,
    ];

    return $this->api_class->request('GET', '/lists/{list_id}/segments/{segment_id}', $tokens, $parameters);
  }

  /**
   * Adds a new segment to a Mailchimp list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $name
   *   The name of the segment.
   * @param array $parameters
   *   Associative array of optional request parameters.
   * @param bool $batch
   *   TRUE to create a new pending batch operation.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/segments/#create-post_lists_list_id_segments
   */
  public function addSegment($list_id, $name, $parameters = [], $batch = FALSE) {
    $tokens = [
      'list_id' => $list_id,
    ];

    $parameters += [
      'name' => $name,
    ];

    return $this->api_class->request('POST', '/lists/{list_id}/segments', $tokens, $parameters, $batch);
  }

  /**
   * Updates a segment associated with a Mailchimp list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param int $segment_id
   *   The ID of the segment.
   * @param string $name
   *   The name of the segment.
   * @param array $parameters
   *   Associative array of optional request parameters.
   * @param bool $batch
   *   TRUE to create a new pending batch operation.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/segments/#edit-patch_lists_list_id_segments_segment_id
   */
  public function updateSegment($list_id, $segment_id, $name, $parameters = [], $batch = FALSE) {
    $tokens = [
      'list_id' => $list_id,
      'segment_id' => $segment_id,
    ];

    $parameters += [
      'name' => $name,
    ];

    return $this->api_class->request('PATCH', '/lists/{list_id}/segments/{segment_id}', $tokens, $parameters, $batch);
  }

  /**
   * Gets information about members of a list segment.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $segment_id
   *   The ID of the segment.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/segments/members/#read-get_lists_list_id_segments_segment_id_members
   */
  public function getSegmentMembers($list_id, $segment_id, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
      'segment_id' => $segment_id,
    ];

    return $this->api_class->request('GET', '/lists/{list_id}/segments/{segment_id}/members', $tokens, $parameters);
  }

  /**
   * Adds a member to a list segment.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $segment_id
   *   The ID of the segment.
   * @param string $email
   *   The email address to add to the segment.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/segments/members/
   */
  public function addSegmentMember($list_id, $segment_id, $email, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
      'segment_id' => $segment_id,
    ];

    $parameters += [
      'email_address' => $email,
    ];

    return $this->api_class->request('POST', '/lists/{list_id}/segments/{segment_id}/members', $tokens, $parameters);
  }

  /**
   * Removes a member from a list segment.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $segment_id
   *   The ID of the segment.
   * @param string $email
   *   The email address to remove from the segment.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/segments/members/#delete-delete_lists_list_id_segments_segment_id_members_subscriber_hash
   */
  public function removeSegmentMember($list_id, $segment_id, $email) {
    $tokens = [
      'list_id' => $list_id,
      'segment_id' => $segment_id,
      'subscriber_hash' => md5(strtolower($email)),
    ];

    return $this->api_class->request('DELETE', '/lists/{list_id}/segments/{segment_id}/members/{subscriber_hash}', $tokens);
  }

  /**
   * Adds tags to a member.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string[] $tags
   *   A list of tags to add.
   * @param string $email
   *   The email address to add the tag to.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see https://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/tags/
   */
  public function addTagsMember($list_id, array $tags, $email, array $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
      'subscriber_hash' => md5(strtolower($email)),
    ];

    foreach ($tags as $tag) {
      $parameters['tags'][] = [
        'name' => $tag,
        'status' => 'active',
      ];
    }

    return $this->api_class->request('POST', '/lists/{list_id}/members/{subscriber_hash}/tags', $tokens, $parameters);
  }

  /**
   * Removes tags from a member.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string[] $tags
   *   A list of tags to remove.
   * @param string $email
   *   The email address to remove the tag from.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see https://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/tags/
   */
  public function removeTagsMember($list_id, array $tags, $email, array $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
      'subscriber_hash' => md5(strtolower($email)),
    ];

    foreach ($tags as $tag) {
      $parameters['tags'][] = [
        'name' => $tag,
        'status' => 'inactive',
      ];
    }

    return $this->api_class->request('POST', '/lists/{list_id}/members/{subscriber_hash}/tags', $tokens, $parameters);
  }

  /**
   * Gets information about webhooks associated with a list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/webhooks/#read-get_lists_list_id_webhooks
   */
  public function getWebhooks($list_id, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
    ];

    return $this->api_class->request('GET', '/lists/{list_id}/webhooks', $tokens, $parameters);
  }

  /**
   * Gets information about a specific webhook associated with a list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $webhook_id
   *   The ID of the webhook.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/webhooks/#read-get_lists_list_id_webhooks_webhook_id
   */
  public function getWebhook($list_id, $webhook_id, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
      'webhook_id' => $webhook_id,
    ];

    return $this->api_class->request('GET', '/lists/{list_id}/webhooks/{webhook_id}', $tokens, $parameters);
  }

  /**
   * Adds a new webhook to a list.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $url
   *   The callback URL the webhook should notify of events.
   * @param array $parameters
   *   Associative array of optional request parameters.
   * @param bool $batch
   *   TRUE to create a new pending batch operation.
   *
   * @return object
   */
  public function addWebhook($list_id, $url, $parameters = [], $batch = FALSE) {
    $tokens = [
      'list_id' => $list_id,
    ];

    $parameters += [
      'url' => $url,
    ];

    return $this->api_class->request('POST', '/lists/{list_id}/webhooks', $tokens, $parameters, $batch);
  }

  /**
   * Deletes a webhook.
   *
   * @param string $list_id
   *   The ID of the list.
   * @param string $webhook_id
   *   The ID of the webhook.
   * @param array $parameters
   *   Associative array of optional request parameters.
   *
   * @return object
   */
  public function deleteWebhook($list_id, $webhook_id, $parameters = []) {
    $tokens = [
      'list_id' => $list_id,
      'webhook_id' => $webhook_id,
    ];

    return $this->api_class->request('DELETE', '/lists/{list_id}/webhooks/{webhook_id}', $tokens, $parameters);
  }

  /**
   * Gets all lists an email address is subscribed to.
   *
   * @param string $email
   *   The email address to get lists for.
   *
   * @return array
   *   Array of subscribed list objects.
   *
   * @throws MailchimpAPIException
   */
  public function getListsForEmail($email) {
    $list_data = $this->getLists();

    $subscribed_lists = [];

    // Check each list for a subscriber matching the email address.
    if ($list_data->total_items > 0) {
      foreach ($list_data->lists as $list) {
        try {
          $member_data = $this->getMemberInfo($list->id, $email);

          if (isset($member_data->id)) {
            $subscribed_lists[] = $list;
          }
        }
        catch (MailchimpAPIException $e) {
          if ($e->getCode() !== 404) {
            // 404 indicates the email address is not subscribed to this list
            // and can be safely ignored. Surface all other exceptions.
            throw new MailchimpAPIException($e->getMessage(), $e->getCode(), $e);
          }
        }
      }
    }

    return $subscribed_lists;
  }

}
