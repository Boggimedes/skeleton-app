<?php

namespace App\Services;

use App\Models\Company\Company;
use App\Models\Company\Name;
use App\Models\Donation\Donation;
use Illuminate\Support\Collection;

class NameService
{
    /**
     * Title Case.
     */
    public static function titleCase(string $string): string
    {
        return ucwords(strtolower($string), " \t\r\n\f\v'.-/");
    }

    /**
     * Normalize a name.
     */
    public static function normalizeName(string $name, $titleCase = false): string
    {
        $commonWords = config('amply.common_company_words');
        $standardized = config('amply.standard_company_abr');
        $re = '/[\"\#$%()*,.\/:;<=>?@\[\\\\\]^_`{|}~]/m';
        $name = strtolower(str_replace(['é'], ['e'], preg_replace($re, '', $name)));
        $name = str_replace($commonWords, $standardized, $name);

        if ($titleCase) {
            $name = self::titleCase($name);
        }

        return $name;
    }

    /**
     * Find a name.
     *
     * @return Company
     */
    public static function findName(string $name): ?Name
    {
        $names = self::nameSearch($name);

        return $names[0] ?? null;
    }

    /**
     * Find a matching company by name.
     */
    public static function findCompany(string $name): ?Company
    {
        $names = self::nameSearch($name);

        return empty($names) ? null : ($names[0]->companies[0] ?? null);
    }

    /**
     * Perform a fuzzy search for a name.
     */
    public static function nameSearch(string $name): Collection
    {
        $searchName = str_replace([' ', '-', "'"], ['', '', "\'"], strtolower(utf8_encode(self::normalizeName($name))));


        // Perform the fuzzy search
        $names = Name::query()
            ->leftJoin('name_company', 'name_company.name_id', '=', 'company_names.id')
            ->select('company_names.*')
            ->where('name', 'like', $name.'%')
            ->get();
        if ($names->isEmpty() || ($names->count() === 1 && $names[0]->name !== $name)) {
            // Perform the fuzzy search
            $names = Name::query()
                ->leftJoin('name_company', 'name_company.name_id', '=', 'company_names.id')
                ->select('company_names.*')
                ->whereFuzzy('name', $name)
                ->get();
        }
        // If no (or one single name that is not an exact match) names were found with the initial fuzzy search, try levenshtein
        if (($names->isEmpty() && strlen($searchName) > 5) || ($names->count() === 1 && $names[0]->name !== $name)) {
            $name = preg_replace('/[^a-zA-Z0-9\s]/', '', $name);
            $normalArray = explode(' ', $name);
            $abbreviatedName = '';
            foreach ($normalArray as $part) {
                if (in_array($part, ['company', 'incorporated', 'corp', 'inc', 'llc', 'corporation'])) {
                    continue;
                }
                $abbreviatedName .= substr($part, 0, 1);
            }
            if (strlen($abbreviatedName) > 2) {
                $abbreviatedName = "`company_names`.name LIKE '%".$abbreviatedName."%' OR";
            } else {
                $abbreviatedName = '';
            }

            $soundEx = "(SOUNDEX(left(`company_names`.search_name,length('$searchName'))) = SOUNDEX('$searchName') AND levenshtein(left(`company_names`.search_name,length('$searchName')), '$searchName')<=3)";
            $levNames = Name::fromQuery("SELECT * FROM (select `company_names`.`id`, `company_names`.`name`, `company_names`.`search_name`, group_concat(name_company.company_id) as company_ids, org_type from `company_names` left join name_company 
                        on name_company.name_id = company_names.id
                        where ( $abbreviatedName $soundEx ) GROUP BY company_names.id) subquery ORDER BY levenshtein(search_name, '$searchName')");
            $names = $names->merge($levNames);
        }

        return $names;
    }

    /**
     * Perform a fuzzy search for a name.
     */
    public static function matchRequestSearch(string $name, $from): mixed
    {
        $searchName = utf8_encode(self::normalizeName($name));

        // Perform the fuzzy search
        $donations = Donation::query()
            ->select('donations.*')
            ->whereNull('company_id')
            ->where('donated_at', '>', $from)
            ->where('status_id', 9)
            ->whereFuzzy('company_name', $searchName)
            ->get();

        return $donations;
    }

    /**
     * Find a company by country for a given Name instance.
     */
    public function findCompanyByCountry(Name $name, string $country, bool $defaultUs = true): ?Company
    {
        // Get the associated companies by country for the given name
        $company = $name->companies()
                        ->where('country', $country)
                        ->first();

        // If no company found, fallback to US if $defaultUs is true
        if (empty($company) && $defaultUs) {
            $company = $name->companies()
                            ->where('country', 'US')
                            ->orWhereNull('country')
                            ->first();
        }

        return $company;
    }

    /**
     * Get the stop words.
     */
    private function getStopWords(): array
    {
        return []; // Add stop words here if needed
    }
}
