<?php

namespace leonverschuren\Lenex;

use leonverschuren\Lenex\Model\Athlete;
use leonverschuren\Lenex\Model\Club;
use leonverschuren\Lenex\Model\Contact;
use leonverschuren\Lenex\Model\Entry;
use leonverschuren\Lenex\Model\Event;
use leonverschuren\Lenex\Model\Lenex;
use leonverschuren\Lenex\Model\Meet;
use leonverschuren\Lenex\Model\MeetInfo;
use leonverschuren\Lenex\Model\Ranking;
use leonverschuren\Lenex\Model\RelayPosition;
use leonverschuren\Lenex\Model\Result;
use leonverschuren\Lenex\Model\Session;
use leonverschuren\Lenex\Model\Split;
use leonverschuren\Lenex\Model\TimeStandard;
use leonverschuren\Lenex\Model\TimeStandardList;
use SimpleXMLElement;

class Converter
{
    public function convert(Lenex $lenex): SimpleXMLElement
    {
        // Создаем корневой элемент с namespace
        $xml = new SimpleXMLElement(
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<LENEX></LENEX>'
        );

        // Добавляем основные элементы
        $this->addAttributeIfSet($xml, 'version', $lenex->getVersion());

        // Добавляем CONSTRUCTOR
        if ($lenex->getConstructor()) {
            $constructor = $xml->addChild('CONSTRUCTOR');
            $this->addAttributeIfSet($constructor, 'name', $lenex->getConstructor()->getName());
            $this->addAttributeIfSet($constructor, 'registration', $lenex->getConstructor()->getRegistration());
            $this->addAttributeIfSet($constructor, 'version', $lenex->getConstructor()->getVersion());

            // Добавляем CONTACT
            if ($lenex->getConstructor()->getContact()) {
                $this->addContact($constructor, $lenex->getConstructor()->getContact());
            }
        }


        // Обрабатываем встречи
        if ($lenex->getMeets()) {
            $meetsXml = $xml->addChild('MEETS');

            foreach ($lenex->getMeets() as $meet) {
                $this->addMeet($meetsXml, $meet);
            }
        }

        if ($lenex->getTimeStandardLists()) {
            $meetsXml = $xml->addChild('TIMESTANDARDLISTS');

            foreach ($lenex->getTimeStandardLists() as $timeStandardList) {
                $this->addTimeStandardLists($meetsXml, $timeStandardList);
            }
        }

        return $xml;
    }

    private function addContact(SimpleXMLElement $parent, Contact $contact): void
    {
        $contactXml = $parent->addChild('CONTACT');
        $this->addAttributeIfSet($contactXml, 'name', $contact->getName());
        $this->addAttributeIfSet($contactXml, 'street', $contact->getStreet());
        $this->addAttributeIfSet($contactXml, 'city', $contact->getCity());
        $this->addAttributeIfSet($contactXml, 'zip', $contact->getZip());
        $this->addAttributeIfSet($contactXml, 'country', $contact->getCountry());
        $this->addAttributeIfSet($contactXml, 'email', $contact->getEmail());
        $this->addAttributeIfSet($contactXml, 'internet', $contact->getInternet());
        $this->addAttributeIfSet($contactXml, 'fax', $contact->getFax());
        $this->addAttributeIfSet($contactXml, 'phone', $contact->getPhone());
    }

    private function addClub(SimpleXMLElement $parent, Club $club): void
    {
        $clubXml = $parent->addChild('CLUB');
        $this->addAttributeIfSet($clubXml,'clubid', $club->getClubId());
        $this->addAttributeIfSet($clubXml,'code', $club->getCode());
        $this->addAttributeIfSet($clubXml,'name', $club->getName());
        $this->addAttributeIfSet($clubXml,'name.en', $club->getNameEn());
        $this->addAttributeIfSet($clubXml,'nation', $club->getNation());
        $this->addAttributeIfSet($clubXml,'type', $club->getType());
        $this->addAttributeIfSet($clubXml,'swrid', $club->getSwrId());
        $this->addAttributeIfSet($clubXml,'shortname', $club->getShortName());
        $this->addAttributeIfSet($clubXml,'shortname.en', $club->getShortNameEn());
        $this->addAttributeIfSet($clubXml,'region', $club->getRegion());
        $this->addAttributeIfSet($clubXml,'number', $club->getNumber());

        $athletes = $clubXml->addChild('ATHLETES');
        foreach ($club->getAthletes() as $athlete) {
            $this->addAthlete($athletes, $athlete);
        }
    }

    private function addAthlete(SimpleXMLElement $parent, Athlete $athlete): void
    {
        $athleteXml = $parent->addChild('ATHLETE');
        $this->addAttributeIfSet($athleteXml, 'athleteid', $athlete->getAthleteId());
        $this->addAttributeIfSet($athleteXml,'birthdate', $athlete->getBirthdate());
        $this->addAttributeIfSet($athleteXml,'firstname', $athlete->getFirstName());
        $this->addAttributeIfSet($athleteXml,'firstname.en', $athlete->getFirstNameEn());
        $this->addAttributeIfSet($athleteXml,'gender', $athlete->getGender());
        $this->addAttributeIfSet($athleteXml,'lastname', $athlete->getLastName());
        $this->addAttributeIfSet($athleteXml,'lastname.en', $athlete->getLastNameEn());
        $this->addAttributeIfSet($athleteXml,'level', $athlete->getLevel());
        $this->addAttributeIfSet($athleteXml,'license', $athlete->getLicense());
        $this->addAttributeIfSet($athleteXml,'nameprefix', $athlete->getNamePrefix());
        $this->addAttributeIfSet($athleteXml,'nation', $athlete->getNation());
        $this->addAttributeIfSet($athleteXml,'passport', $athlete->getPassport());
        $this->addAttributeIfSet($athleteXml,'swrid', $athlete->getSwrid());

        $results = $athleteXml->addChild('RESULTS');
        foreach ($athlete->getResults() as $result) {
            $this->addResult($results, $result);
        }

        if (!empty($athlete->getEntries())) {
            $entries = $athleteXml->addChild('ENTRIES');
            foreach ($athlete->getEntries() as $entry) {
                $this->addEntry($entries, $entry);
            }
        }
    }

    private function addResult(SimpleXMLElement $parent, Result $result): void
    {
        $xml = $parent->addChild('RESULT');
        $this->addAttributeIfSet($xml,'comment', $result->getComment());
        $this->addAttributeIfSet($xml,'eventid', $result->getEventid());
        $this->addAttributeIfSet($xml,'heatid', $result->getHeatId());
        $this->addAttributeIfSet($xml,'lane', $result->getLane());
        $this->addAttributeIfSet($xml,'points', $result->getPoints());
        $this->addAttributeIfSet($xml,'reactiontime', $result->getReactionTime());
        $this->addAttributeIfSet($xml,'resultid', $result->getResultId());
        $this->addAttributeIfSet($xml,'status', $result->getStatus());
        $this->addAttributeIfSet($xml,'swimtime', $result->getSwimTime());
        $this->addAttributeIfSet($xml,'entrytime', $result->getEntryTime());
        $this->addAttributeIfSet($xml,'entrycourse', $result->getEntryCourse());
        $this->addAttributeIfSet($xml,'late', $result->getLate());

        if ($relayPositions = $result->getRelayPositions()) {
            $relayPositionsXml = $xml->addChild('RELAYPOSITIONS');
            foreach ($relayPositions as $relayPosition) {
                $this->addRelayPosition($relayPositionsXml, $relayPosition);
            }
        }
        if ($splits = $result->getSplits()) {
            $splitsXml = $xml->addChild('SPLITS');
            foreach ($splits as $split) {
                $this->addSplit($splitsXml, $split);
            }
        }
    }

    private function addSplit(SimpleXMLElement $parent, Split $model): void
    {
        $xml = $parent->addChild('SPLIT');
        $this->addAttributeIfSet($xml,'distance', $model->getDistance());
        $this->addAttributeIfSet($xml,'swimtime', $model->getSwimTime());
    }

    private function addRelayPosition(SimpleXMLElement $parent, RelayPosition $model): void
    {
        $xml = $parent->addChild('RELAYPOSITIONS');
        $this->addAttributeIfSet($xml,'athleteid', $model->getAthleteId());
        $this->addAttributeIfSet($xml,'number', $model->getNumber());
        $this->addAttributeIfSet($xml,'reactiontime', $model->getReactionTime());
        $this->addAttributeIfSet($xml,'status', $model->getStatus());

        if ($athlete = $model->getAthlete()) {
            $this->addAthlete($xml, $athlete);
        }
        if ($meetInfo = $model->getMeetInfo()) {
            $this->addMeetInfo($xml, $meetInfo);
        }
    }

    private function addEntry(SimpleXMLElement $parent, Entry $entry): void
    {
        $entryXml = $parent->addChild('ENTRY');
        $this->addAttributeIfSet($entryXml,'entrytime', $entry->getEntryTime());
        $this->addAttributeIfSet($entryXml,'eventid', $entry->getEventId());

        if ($meetInfo = $entry->getMeetInfo()) {
            $this->addMeetInfo($entryXml, $meetInfo);
        }
    }

    private function addMeetInfo(SimpleXMLElement $parent, MeetInfo $meetInfo): void
    {
        $meetInfoXml = $parent->addChild('MEETINFO');
        $this->addAttributeIfSet($meetInfoXml,'city', $meetInfo->getCity());
        $this->addAttributeIfSet($meetInfoXml,'course', $meetInfo->getCourse());
        $this->addAttributeIfSet($meetInfoXml,'date', $meetInfo->getDate());
        $this->addAttributeIfSet($meetInfoXml,'name', $meetInfo->getName());
        $this->addAttributeIfSet($meetInfoXml,'nation', $meetInfo->getNation());
    }

    private function addMeet(SimpleXMLElement $parent, Meet $meet): void
    {
        $meetXml = $parent->addChild('MEET');
        $this->addAttributeIfSet($meetXml, 'name', $meet->getName());
        $this->addAttributeIfSet($meetXml, 'name.en', $meet->getNameEn());
        $this->addAttributeIfSet($meetXml, 'city', $meet->getCity());
        $this->addAttributeIfSet($meetXml, 'course', $meet->getCourse());
        $this->addAttributeIfSet($meetXml, 'reservecount', $meet->getReserveCount());
        $this->addAttributeIfSet($meetXml, 'startmethod', $meet->getStartMethod());
        $this->addAttributeIfSet($meetXml, 'timing', $meet->getTiming());
        $this->addAttributeIfSet($meetXml, 'nation', $meet->getNation());
        $this->addAttributeIfSet($meetXml, 'deadline', $meet->getDeadline());
        $this->addAttributeIfSet($meetXml, 'altitude', $meet->getAltitude());
        $this->addAttributeIfSet($meetXml, 'city.en', $meet->getCityEn());
        $this->addAttributeIfSet($meetXml, 'deadlinetime', $meet->getDeadlineTime());
        $this->addAttributeIfSet($meetXml, 'entrystartdate', $meet->getEntryStartDate());
        $this->addAttributeIfSet($meetXml, 'entrytype', $meet->getEntryType());
        $this->addAttributeIfSet($meetXml, 'hostclub', $meet->getHostClub());
        $this->addAttributeIfSet($meetXml, 'hostclub.url', $meet->getHostClubUrl());
        $this->addAttributeIfSet($meetXml, 'maxentries', $meet->getMaxEntries());
        $this->addAttributeIfSet($meetXml, 'number', $meet->getNumber());
        $this->addAttributeIfSet($meetXml, 'organizer', $meet->getOrganizer());
        $this->addAttributeIfSet($meetXml, 'organizer.url', $meet->getOrganizerUrl());
        $this->addAttributeIfSet($meetXml, 'result.url', $meet->getResultUrl());
        $this->addAttributeIfSet($meetXml, 'state', $meet->getState());
        $this->addAttributeIfSet($meetXml, 'swrid', $meet->getSwrId());
        $this->addAttributeIfSet($meetXml, 'type', $meet->getType());
        $this->addAttributeIfSet($meetXml, 'withdrawuntil', $meet->getWithdrawUntil());

        // Добавляем AGEDATE
        if ($meet->getAgeDate()) {
            $ageDate = $meetXml->addChild('AGEDATE');
            $this->addAttributeIfSet($ageDate, 'value', $meet->getAgeDate()->getValue());
            $this->addAttributeIfSet($ageDate, 'type', $meet->getAgeDate()->getType());
        }

        // Добавляем POOL
        if ($meet->getPool()) {
            $pool = $meetXml->addChild('POOL');
            $this->addAttributeIfSet($pool, 'name', $meet->getPool()->getName());
            $this->addAttributeIfSet($pool, 'lanemax', $meet->getPool()->getLaneMax());
            $this->addAttributeIfSet($pool, 'lanemin', $meet->getPool()->getLaneMin());
        }

        // Добавляем FACILITY
        if ($meet->getFacility()) {
            $facility = $meetXml->addChild('FACILITY');
            $this->addAttributeIfSet($facility, 'city', $meet->getFacility()->getCity());
            $this->addAttributeIfSet($facility, 'name', $meet->getFacility()->getName());
            $this->addAttributeIfSet($facility, 'nation', $meet->getFacility()->getNation());
            $this->addAttributeIfSet($facility, 'state', $meet->getFacility()->getState());
            $this->addAttributeIfSet($facility, 'street', $meet->getFacility()->getStreet());
            $this->addAttributeIfSet($facility, 'street2', $meet->getFacility()->getStreet2());
            $this->addAttributeIfSet($facility, 'zip', $meet->getFacility()->getZip());
        }

        // Добавляем POINTTABLE
        if ($meet->getPointTable()) {
            $pointTable = $meetXml->addChild('POINTTABLE');
            $this->addAttributeIfSet($pointTable, 'pointtableid', $meet->getPointTable()->getPointTableId());
            $this->addAttributeIfSet($pointTable, 'name', $meet->getPointTable()->getName());
            $this->addAttributeIfSet($pointTable, 'version', $meet->getPointTable()->getVersion());
        }

        if ($meet->getContact()) {
            $this->addContact($meetXml, $meet->getContact());
        }

        // Добавляем сессии
        if ($meet->getSessions()) {
            $sessionsXml = $meetXml->addChild('SESSIONS');
            foreach ($meet->getSessions() as $session) {
                $this->addSession($sessionsXml, $session);
            }
        }

        if ($meet->getClubs()) {
            $clubs = $meetXml->addChild('CLUBS');
            foreach ($meet->getClubs() as $club) {
                $this->addClub($clubs, $club);
            }
        }
    }

    private function addSession(SimpleXMLElement $parent, Session $session): void
    {
        $sessionXml = $parent->addChild('SESSION');
        $this->addAttributeIfSet($sessionXml, 'date', $session->getDate());
        $this->addAttributeIfSet($sessionXml, 'daytime', $session->getDayTime());
        $this->addAttributeIfSet($sessionXml, 'endtime', $session->getEndTime());
        $this->addAttributeIfSet($sessionXml, 'number', $session->getNumber());
        $this->addAttributeIfSet($sessionXml, 'name', $session->getName());
        $this->addAttributeIfSet($sessionXml, 'warmupfrom', $session->getWarmUpFrom());
        $this->addAttributeIfSet($sessionXml, 'warmupuntil', $session->getWarmUpUntil());
        $this->addAttributeIfSet($sessionXml, 'officialmeeting', $session->getOfficialMeeting());

        // Добавляем события
        if ($session->getEvents()) {
            $eventsXml = $sessionXml->addChild('EVENTS');
            foreach ($session->getEvents() as $event) {
                $this->addEvent($eventsXml, $event);
            }
        }
    }

    private function addEvent(SimpleXMLElement $parent, Event $event): void
    {
        $eventXml = $parent->addChild('EVENT');
        $this->addAttributeIfSet($eventXml, 'eventid', $event->getEventId());
        $this->addAttributeIfSet($eventXml, 'daytime', $event->getDayTime());
        $this->addAttributeIfSet($eventXml, 'gender', $event->getGender());
        $this->addAttributeIfSet($eventXml, 'number', $event->getNumber());
        $this->addAttributeIfSet($eventXml, 'order', $event->getOrder());
        $this->addAttributeIfSet($eventXml, 'round', $event->getRound());
        $this->addAttributeIfSet($eventXml, 'preveventid', $event->getPrevEventId());

        // Добавляем SWIMSTYLE
        if ($event->getSwimStyle()) {
            $swimStyle = $eventXml->addChild('SWIMSTYLE');
            $this->addAttributeIfSet($swimStyle, 'distance', $event->getSwimStyle()->getDistance());
            $this->addAttributeIfSet($swimStyle, 'relaycount', $event->getSwimStyle()->getRelayCount());
            $this->addAttributeIfSet($swimStyle, 'stroke', $event->getSwimStyle()->getStroke());
        }

        // Добавляем AGEGROUPS
        if ($event->getAgeGroups()) {
            $ageGroupsXml = $eventXml->addChild('AGEGROUPS');
            foreach ($event->getAgeGroups() as $ageGroup) {
                $ageGroupXml = $ageGroupsXml->addChild('AGEGROUP');
                $this->addAttributeIfSet($ageGroupXml, 'agegroupid', $ageGroup->getAgeGroupId());
                $this->addAttributeIfSet($ageGroupXml, 'agemax', $ageGroup->getAgeMax());
                $this->addAttributeIfSet($ageGroupXml, 'agemin', $ageGroup->getAgeMin());
                $this->addAttributeIfSet($ageGroupXml, 'name', $ageGroup->getName());
                $this->addAttributeIfSet($ageGroupXml, 'handicap', $ageGroup->getHandicap());

                if ($ageGroup->getRankings()) {
                    $rankingsXml = $ageGroupXml->addChild('RANKINGS');
                    foreach ($ageGroup->getRankings() as $ranking) {
                        $this->addRanking($rankingsXml, $ranking);
                    }
                }
            }
        }

        if ($event->getHeats()) {
            $heatsXml = $eventXml->addChild('HEATS');
            foreach ($event->getHeats() as $heat) {
                $heatXml = $heatsXml->addChild('HEAT');
                $this->addAttributeIfSet($heatXml, 'agegroupid', $heat->getAgeGroupId());
                $this->addAttributeIfSet($heatXml, 'daytime', $heat->getDayTime());
                $this->addAttributeIfSet($heatXml, 'final', $heat->getFinal());
                $this->addAttributeIfSet($heatXml, 'heatid', $heat->getHeatId());
                $this->addAttributeIfSet($heatXml, 'number', $heat->getNumber());
                $this->addAttributeIfSet($heatXml, 'order', $heat->getOrder());
                $this->addAttributeIfSet($heatXml, 'status', $heat->getStatus());
            }
        }

        // Добавляем TIMESTANDARDREFS
        if ($event->getTimeStandardRefs()) {
            $timeStandardRefsXml = $eventXml->addChild('TIMESTANDARDREFS');
            foreach ($event->getTimeStandardRefs() as $timeStandardRef) {
                $timeStandardRefXml = $timeStandardRefsXml->addChild('TIMESTANDARDREF');
                $this->addAttributeIfSet($timeStandardRefXml, 'marker', $timeStandardRef->getMarker());
                $this->addAttributeIfSet($timeStandardRefXml, 'timestandardlistid', $timeStandardRef->getTimeStandardListId());
            }
        }
    }

    private function addRanking(SimpleXMLElement $parent, Ranking $ranking): void
    {
        $xml = $parent->addChild('RANKING');
        $this->addAttributeIfSet($xml, 'order', $ranking->getOrder());
        $this->addAttributeIfSet($xml, 'place', $ranking->getPlace());
        $this->addAttributeIfSet($xml, 'resultid', $ranking->getResultId());
    }
    private function addTimeStandardLists(SimpleXMLElement $parent, TimeStandardList $timeStandardList): void
    {
        $meetXml = $parent->addChild('TIMESTANDARDLIST');
        $this->addAttributeIfSet($meetXml, 'name', $timeStandardList->getName());
        $this->addAttributeIfSet($meetXml, 'code', $timeStandardList->getCode());
        $this->addAttributeIfSet($meetXml, 'course', $timeStandardList->getCourse());
        $this->addAttributeIfSet($meetXml, 'gender', $timeStandardList->getGender());
        $this->addAttributeIfSet($meetXml, 'handicap', $timeStandardList->getHandicap());
        $this->addAttributeIfSet($meetXml, 'timestandardlistid', $timeStandardList->getTimeStandardListId());
        $this->addAttributeIfSet($meetXml, 'type', $timeStandardList->getType());


        if ($timeStandardList->getAgeGroup()) {
            $ageGroup = $timeStandardList->getAgeGroup();
            $ageGroupXml = $meetXml->addChild('AGEGROUP');
            $this->addAttributeIfSet($ageGroupXml, 'agegroupid', $ageGroup->getAgeGroupId());
            $this->addAttributeIfSet($ageGroupXml, 'agemax', $ageGroup->getAgeMax());
            $this->addAttributeIfSet($ageGroupXml, 'agemin', $ageGroup->getAgeMin());
        }

        if ($timeStandardList->getTimeStandards()) {
            $timeStandardsXml = $meetXml->addChild('TIMESTANDARDS');
            foreach ($timeStandardList->getTimeStandards() as $timeStandard) {
                $this->addTimeStandard($timeStandardsXml, $timeStandard);
            }
        }
    }

    private function addTimeStandard(SimpleXMLElement $parent, TimeStandard $timeStandard)
    {
        $timeStandardXml = $parent->addChild('TIMESTANDARD');
        $this->addAttributeIfSet($timeStandardXml, 'swimtime', $timeStandard->getSwimTime());
        if ($timeStandard->getSwimStyle()) {
            $swimStyle = $timeStandardXml->addChild('SWIMSTYLE');
            $this->addAttributeIfSet($swimStyle, 'distance', $timeStandard->getSwimStyle()->getDistance());
            $this->addAttributeIfSet($swimStyle, 'relaycount', $timeStandard->getSwimStyle()->getRelayCount());
            $this->addAttributeIfSet($swimStyle, 'stroke', $timeStandard->getSwimStyle()->getStroke());
        }
    }

    private function addAttributeIfSet(SimpleXMLElement $element, string $name, mixed $value): void
    {
        if ($value instanceof \DateTimeInterface) {
            $value = $value->format('Y-m-d');
        }
        if ($value !== null) {
            $element->addAttribute($name, $value);
        }
    }
}