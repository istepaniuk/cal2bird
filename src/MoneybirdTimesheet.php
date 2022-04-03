<?php

namespace CalBird;

use CalBird\Timesheet\EntryId;
use CalBird\Timesheet\TimeEntry;
use CalBird\Timesheet\Timesheet;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class MoneybirdTimesheet implements Timesheet
{
    const MONEYBIRD_API_URL = 'https://moneybird.com/api/v2';
    const NOTE_PREFIX_FOR_ID = 'cal2bird_id=';

    private Client $client;
    private array $requestOptions;
    private string $administrationId = '';
    private string $userId = '';
    private LoggerInterface $logger;
    private array $cachedEntries = [];
    private array $projectIdsByName = [];

    public function __construct(string $bearerToken, ?LoggerInterface $logger = null)
    {
        $this->client = new Client(
            [
                'base_uri' => self::MONEYBIRD_API_URL,
                'timeout' => 10.0,
            ]
        );
        $this->requestOptions = [
            'headers' => [
                'Authorization' => 'Bearer '.$bearerToken,
                'Content-Type' => 'application/json',
            ],
        ];

        $this->logger = $logger ?? new NullLogger();
    }

    public function save(TimeEntry $entry): void
    {
        if (!$this->entryExists($entry)) {
            $this->createEntry($entry);
            $this->logger->info('Created: '.$entry);
        } else {
            $this->logger->info('Skipped: '.$entry);
        }
    }

    private function administrationBaseUrl(): string
    {
        return self::MONEYBIRD_API_URL.'/'.$this->administrationId();
    }

    private function administrationId(): string
    {
        if (!empty($this->administrationId)) {
            return $this->administrationId;
        }

        $response = $this->client->get(
            self::MONEYBIRD_API_URL.'/administrations.json',
            $this->requestOptions
        );
        $decoded = json_decode($response->getBody());
        $this->administrationId = reset($decoded)->id;
        $this->logger->debug('Administration ID is: '.$this->administrationId);

        return $this->administrationId;
    }

    private function userId(): string
    {
        if (!empty($this->userId)) {
            return $this->userId;
        }

        $users = $this->request('GET', '/users.json');
        $this->userId = reset($users)['id'];

        return $this->userId;
    }

    private function projectIdByName(string $name): string
    {
        if (isset($this->projectIdsByName[$name])) {
            return $this->projectIdsByName[$name];
        }

        $projects = $this->request('GET', '/projects.json?filter=state:active');
        foreach ($projects as $project) {
            $this->projectIdsByName[$project['name']] = $project['id'];
            $this->logger->debug('- Found project:'.$project['name'].': '.$project['id']);
        }

        return $this->projectIdsByName[$name];
    }

    private function entryExists(TimeEntry $entry)
    {
        $results = $this->entriesForTheWeekOfDate($entry->start());

        foreach ($results as $result) {
            if ($this->hasANoteContainingId($result['notes'] ?? [], $entry->id())) {
                return true;
            }
        }

        return false;
    }

    private function hasANoteContainingId(array $notes, EntryId $id)
    {
        foreach ($notes as $note) {
            if ($note['note'] == self::NOTE_PREFIX_FOR_ID.(string) $id) {
                return true;
            }
        }

        return false;
    }

    private function createEntry(TimeEntry $entry)
    {
        $timeEntryData = [
            'time_entry' => [
                'started_at' => $entry->start()->format(DATE_ATOM),
                'ended_at' => $entry->end()->format(DATE_ATOM),
                'user_id' => $this->userId(),
                'description' => (string) $entry->description(),
                'billable' => $entry->billable(),
            ],
        ];

        if (!$entry->project()->isNone()) {
            $timeEntryData['time_entry']['project_id'] = $this->projectIdByName($entry->project());
        }

        $result = $this->request('POST', '/time_entries.json', json_encode($timeEntryData));
        $moneybirdTimeEntryId = $result['id'];

        $noteData = [
            'note' => [
                'note' => self::NOTE_PREFIX_FOR_ID.(string) $entry->id(),
                'todo' => false,
            ],
        ];
        $this->request('POST', "/time_entries/$moneybirdTimeEntryId/notes.json", json_encode($noteData));
    }

    private function request(string $method, string $url, string $body = '')
    {
        $options = $this->requestOptions;

        if ($body != '') {
            $options['body'] = $body;
        }

        $response = $this->client->request(
            $method,
            $this->administrationBaseUrl().$url,
            $options
        );

        return json_decode($response->getBody(), true);
    }

    private function entriesForTheWeekOfDate(\DateTimeInterface $date)
    {
        $formattedDate = $date->format('Y-m-d');
        if (isset($this->cachedEntries[$formattedDate])) {
            return $this->cachedEntries[$formattedDate];
        }

        $entries = $this->request('GET', "/time_entries.json?filter=day:$formattedDate");
        $this->cachedEntries[$formattedDate] = $entries;
        $count = \count($entries);
        $this->logger->debug(" - Found $count entries for date $formattedDate");

        return $entries;
    }
}
