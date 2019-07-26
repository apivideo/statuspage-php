<?php

namespace ApiVideo\StatusPage\Model;

use ApiVideo\StatusPage\Traits\Getter;
use DateTimeImmutable;
use Exception;

/**
 * @property-read string id
 * @property-read DateTimeImmutable created_at
 * @property-read DateTimeImmutable updated_at
 * @property-read string name
 * @property-read string page_description
 * @property-read string headline
 * @property-read string branding
 * @property-read string subdomain
 * @property-read string domain
 * @property-read string url
 * @property-read string support_url
 * @property-read string hidden_from_search
 * @property-read bool allow_page_subscribers
 * @property-read bool allow_incident_subscribers
 * @property-read bool allow_email_subscribers
 * @property-read bool allow_sms_subscribers
 * @property-read bool allow_rss_atom_feeds
 * @property-read bool allow_webhook_subscribers
 * @property-read string notifications_from_email
 * @property-read string notifications_email_footer
 * @property-read int activity_score
 * @property-read string twitter_username
 * @property-read bool viewers_must_be_team_members
 * @property-read string ip_restrictions
 * @property-read string city
 * @property-read string state
 * @property-read string country
 * @property-read string time_zone
 * @property-read string css_body_background_color
 * @property-read string css_font_color
 * @property-read string css_light_font_color
 * @property-read string css_greens
 * @property-read string css_yellows
 * @property-read string css_oranges
 * @property-read string css_blues
 * @property-read string css_reds
 * @property-read string css_border_color
 * @property-read string css_graph_color
 * @property-read string css_link_color
 * @property-read string favicon_logo
 * @property-read string transactional_logo
 * @property-read string hero_cover
 * @property-read string email_logo
 * @property-read string twitter_logo
 */
final class Page
{
    use Getter;

    /** @var array */
    private $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     * @return Page
     * @throws Exception
     */
    public static function fromArray(array $data)
    {
        return new Page(array_merge($data, [
            'createdAt'                     => isset($data['created_at']) ? new DateTimeImmutable($data['created_at']) : null,
            'updatedAt'                     => isset($data['updated_at']) ? new DateTimeImmutable($data['updated_at']) : null,
            'hidden_from_search'            => (bool) $data['hidden_from_search'],
            'allow_page_subscribers'        => (bool) $data['allow_page_subscribers'],
            'allow_incident_subscribers'    => (bool) $data['allow_incident_subscribers'],
            'allow_email_subscribers'       => (bool) $data['allow_email_subscribers'],
            'allow_sms_subscribers'         => (bool) $data['allow_sms_subscribers'],
            'allow_rss_atom_feeds'          => (bool) $data['allow_rss_atom_feeds'],
            'allow_webhook_subscribers'     => (bool) $data['allow_webhook_subscribers'],
            'activity_score'                => (int) $data['activity_score'],
            'viewers_must_be_team_members'  => (bool) $data['viewers_must_be_team_members'],
        ]));
    }
}
