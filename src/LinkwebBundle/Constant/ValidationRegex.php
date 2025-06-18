<?php

namespace App\LinkwebBundle\Constant;

readonly class ValidationRegex
{
    final public const PHONE_SEPARATOR = '[-*.\s]';
    /**
     * Phone number regex to retrieve phone numbers with or without indicative and with misc separators.
     *
     * @example +33 6 12 34 56 78
     *          +33-6-12-34-56-78
     *          06 12 34 56 78
     *          06.12.34.56.78
     *          06*12*34*56*78
     *          06-12-34-56-78
     *          0612345678
     *          +33(0)612345678
     */
    final public const PHONE = '/(\b)?((\+\b\d{1,4}|\b0)\d?(\(0\))?\d?([-*.\s]*\d\d[-*.\s]*?){4})(\b)?/';
    final public const PHONE_INDICATIVE = '/(\b)?(\+1|\+1242|\+1246|\+1264|\+1268|\+1284|\+1340|\+1441|\+1473|\+1649|\+1664|\+1670|\+1671|\+1684|\+1758|\+1767|\+1784|\+1849|\+1868|\+1869|\+1876|\+1939|\+20|\+211|\+212|\+213|\+216|\+218|\+220|\+221|\+222|\+223|\+224|\+225|\+226|\+227|\+228|\+229|\+230|\+231|\+232|\+233|\+234|\+235|\+236|\+237|\+238|\+239|\+240|\+241|\+242|\+243|\+244|\+245|\+246|\+248|\+249|\+250|\+251|\+252|\+253|\+254|\+255|\+256|\+257|\+258|\+260|\+261|\+262|\+263|\+264|\+265|\+266|\+267|\+268|\+269|\+27|\+290|\+291|\+297|\+298|\+299|\+30|\+31|\+32|\+33|\+34|\+345|\+350|\+351|\+352|\+353|\+354|\+355|\+356|\+357|\+358|\+359|\+36|\+370|\+371|\+372|\+373|\+374|\+375|\+376|\+377|\+378|\+379|\+380|\+381|\+382|\+385|\+386|\+387|\+389|\+39|\+40|\+41|\+420|\+421|\+423|\+43|\+44|\+45|\+46|\+47|\+48|\+49|\+500|\+501|\+502|\+503|\+504|\+505|\+506|\+507|\+508|\+509|\+51|\+52|\+53|\+54|\+55|\+56|\+57|\+58|\+590|\+591|\+593|\+594|\+595|\+596|\+597|\+598|\+599|\+60|\+61|\+62|\+63|\+64|\+65|\+66|\+670|\+672|\+673|\+674|\+675|\+676|\+677|\+678|\+679|\+680|\+681|\+682|\+683|\+685|\+686|\+687|\+688|\+689|\+690|\+691|\+692|\+7|\+77|\+81|\+82|\+84|\+850|\+852|\+853|\+855|\+856|\+86|\+872|\+880|\+886|\+90|\+91|\+92|\+93|\+94|\+95|\+960|\+961|\+962|\+963|\+964|\+965|\+966|\+967|\+968|\+970|\+971|\+972|\+973|\+974|\+975|\+976|\+977|\+98|\+992|\+993|\+994|\+995|\+996|\+998|0)\d{9}(\b)?/';
    final public const MOBILE_PHONE_FR = '/(?=(?:\+336|\+337|06|07))((?:\+\d+[-*.\s](?:\(0\))?\d[-*.\s])(?:\d{2}[-*.\s]?){4}|\b(?:\d{2}[-*.\s]?){5})\b/';
    /**
     * Match common address formats and escape html tags and special characters. More strict than ADDRESS.
     *
     * @example "8 Rue Mauryded 31000 Toulouse"
     *          "25 Boulevard Haussmann 75009"
     *          "10 Avenue Victor Hugo 69006"
     *          "5 Allée des Acacias 64200"
     *          "12 Rue Lafayette 44000"
     *          "18 Faubourg Saint-Honoré 75008"
     *          "50 Esplanade Charles de Gaulle 34000"
     */
    final public const SCRAP_ADDRESS = "/(?i)(\d{1,5})\s([^\d,<>]+?)\s(?:rue|avenue|av|boulevard|bd|place|route|chemin|impasse|allée|passage|quai|cours|square|cité|voie|faubourg|esplanade|rocade|rond-point|rd-pt)?\s*([^\d,<>]+)\s(\d{5})\s*([^\d,<>]+)(?=\s*<|$)/";
    final public const EMAIL = '/\b[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(?!(?:png|jpg|jpeg|gif|bmp|tiff|webp|svg|ico|heif|heic|pdf|doc|docx|xls|xlsx|ppt|pptx|txt|rtf|csv|xml|json|zip|rar|7z|tar|gz|bz2|exe|dll|dmg|iso|mp3|wav|ogg|flac|mp4|avi|mov|wmv|mkv|flv|webm|psd|ai|eps|indd|sketch|apk|ipa|bat|sh|log|bak|tmp|bin))[a-zA-Z]{2,}\b/';
}
