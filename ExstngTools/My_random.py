__author__ = "Soham Tripathy (stripathy) https://www.linkedin.com/in/soham-tripathy-115986200/"

"""
To run this script from the terminal, the command is
'python skills_engine_fetch_missing_skills.py -l en'

The language code can be changed to the desired one. The -lang_code flag may be omitted if the language to be fetched is English.

Output of the script:
1. vs-skills-engine-us-west-2-eightfolddemo-default-skills.com.csv - has the default internal skills of eightfold.
2. all_skills_{language_code}.csv - has the skills and the number fetched from eightfoldxx.com with filter profile_language: language 
3. difference_skills_in_external_not_in_internal.csv - the set of skills which are missing. Columns are ['stemmed_skill','List of corresponding skills']
4. Number_Missingh_Skills.csv. Columns are ['Skill','Number']

Nomenclature:
internal_skills mean the skills in Eightfold database
external_skills mean the skills in third party database

"""
import time
import csv
import argparse
import os
import boto3
import pandas as pd
from nlp import nlp_init
from gevent import monkey
monkey.patch_all()
import nlp
from search.search import get_search_results
from user import user_login
from ranking.skill_engine import deduped_skill
import ranking.skill_engine.skills_engine_onboarding_utils as lang_detect

# Global variable
skills_dict = {}


def get_all_skills_nlp_skills_trie():
    """
    Retrieve skills from Eightfold internal database */skills_engine/tries/eightfolddemo-default-skills.com.marisa

    Parameters:
    - None

    Returns:
    - path of .csv file having all skills in internal database
    - columns in the csv file : ['Skill']

    """
    csv_file = "all_skills_internal_nlp_skills_trie.csv"
    if os.path.isfile(csv_file):
        return os.path.abspath(csv_file)
    all_std_skills = nlp.standard_skills_trie.keys()  # All languages present
    with open(csv_file, "a", newline="") as file:
        writer = csv.writer(file)
        if not os.path.isfile(csv_file):
            writer.writerow(["Skill"])

        for i in range(0, len(all_std_skills)):
            skill = all_std_skills[i]
            writer.writerow([skill])
    return os.path.abspath(csv_file)


def get_all_skills_external_path(
    current_user,
    language,
    term="",
    callerid="fetch_all_pdl_profile_skills",
    facet_fields=None,
    fields="*",
    facet_limit=1000000000,
):
    """
    Retrieve skills from all profiles

    Parameters:
    - term (str): Search term to filter the skills.
    - field_query: To filter out the results based on the field query provided
    - callerid (str): Identify who has called teh function
    - current_user (object): Information about the current user.
    - facet_fields (str): List of fields to use for faceting.
    - fields (str): Fields to include in the output. (If we are providing facet_fields we dont need fields anymore)
    - facet_limit (int): Maximum number of facets to return.
    - languages: the language codes you want to fetch

    Note:
    If you enter the language as en, then the filter query will be set to 'profile_language:en'. This helps to fetch the skills in english language

    Returns:
    - path of the .csv files having all the skills
    - The .csv files has two columns ['Skill','Number']
    """
    assert current_user, "Need to provide current_user"
    assert language is not None, "Please provide the language you want to fetch skills for"
    field_query = [f'profile_language:"{language}"']

    start_time = time.time()
    csv_file = "all_skills_" + language + ".csv"
    if os.path.isfile(csv_file):
        return os.path.abspath(csv_file)

    res, code = get_search_results(
        term=term,
        callerid=callerid,
        fq=field_query,
        current_user=current_user,
        facet_fields=facet_fields,
        fields=fields,
        facet_limit=facet_limit,
    )

    assert code == 200, "Search query unsuccessful"
    skills_list = res["facet_counts"]["facet_fields"]["profile.skills"]
    with open(csv_file, "a", newline="") as file:
        writer = csv.writer(file)
        if not os.path.isfile(csv_file):
            writer.writerow(["Skill", "Number"])
        for i in range(0, len(skills_list), 2):
            skill = skills_list[i]
            number = skills_list[i + 1]
            writer.writerow([skill, number])
    print(f"Time to fetch all profile skills: {time.time()-start_time}")
    return os.path.abspath(csv_file)


def fetch_all_language_codes(current_user):
    """
    A function to fetch all language codes being used in Eightfold.AI using solr query

    Parameters:
    - None

    Returns:
    - A list of the language codes fetched from SOLR query

    """
    res, code = get_search_results(
        term="",
        callerid="fetch_all_languages_from_solr",
        current_user=current_user,
        facet_fields="profile_language",
        fields="*",
        facet_limit=10000,  # assuming maximum 1000 languages might get added in the future.
    )
    assert code == 200, "Search query unsuccessful"

    language_list = [
        ele for (i, ele) in enumerate(res["facet_counts"]["facet_fields"]["profile_language"]) if i % 2 == 0
    ]
    return language_list


def get_all_skills_third_party_path(language):
    """
    Function to get the path of external skills.csv of the language provided

    Parameters:
    - None

    Returns:
    - the path of external_skills.csv
    - Columns of the .csv file: [Skill, Number]
    """
    current_user = user_login.get_import_user("eightfoldxx.com")
    language_list = fetch_all_language_codes(current_user)

    assert language in language_list, "Please enter valid langauge code"

    get_path_all_external_skills_csv = get_all_skills_external_path(
        current_user=current_user,
        language=language,
        term="",
        callerid="fetch_all_pdl_profile_skills",
        facet_fields="profile.skills",
        fields="*",
        facet_limit=1000000000,
    )
    return get_path_all_external_skills_csv


def get_internal_skills_s3_bucket(bucket_name):
    """
    Returns
    - a pandas dataframe of  skills data
    Columns: [Skill]
    """
    s3 = boto3.client("s3")
    if not os.path.isfile(f"{bucket_name}-eightfolddemo-default-skills.com.csv"):
        s3.download_file(
            bucket_name,
            "standard_skills/eightfolddemo-default-skills.com.csv",
            f"{bucket_name}-eightfolddemo-default-skills.com.csv",
        )
        print(f"Downloaded file from bucket {bucket_name}")
    df_bucket_internal_skills = pd.read_csv(f"{bucket_name}-eightfolddemo-default-skills.com.csv")
    return df_bucket_internal_skills


def get_external_dataframe(language):
    """
    Returns
    - a pandas dataframe of external skills data
    Columns: [Skill, Number]
    """
    path_external_skills = get_all_skills_third_party_path(language)
    df = pd.read_csv(path_external_skills)
    df.dropna(subset=["Skill"], inplace=True)
    return df


def check_english(stemmed_skill):
    """
    Pure function

    Used to check the language of the skill in default internal skills
    """
    if lang_detect.get_lang_from_stem(stemmed_skill) == "en":  # stems starting with $$ are from other language
        return True
    return False


def get_stem(unstemmed_skill):
    """
    Main function: to get the stemmed form of the skill
    Auxiliary function: to also populate the skills dict, a dictionary of {stemmed_skill: actual skill}

    Parameters:
    - None

    Returns:
    - stemmed skill
    """
    global skills_dict
    stem = deduped_skill.get_stemmed_form(unstemmed_skill, lang="en")
    if stem not in skills_dict.keys():
        skills_dict[stem] = []
        skills_dict[stem].append(unstemmed_skill)
    else:
        skills_dict[stem].append(unstemmed_skill)
    return stem


def get_num(df_external_skills_reduced):
    """
    Function to get the dictionary of {skill: its number} in the external skills dataframe

    Parameters:
    - the reduced external skills dataframe

    Returns:
    - a dictionary of {skill:Number}
    """
    skills_num_dict = {}
    for _, row in df_external_skills_reduced.iterrows():
        if row["Skill"] not in skills_num_dict.keys():
            skills_num_dict[row["Skill"]] = row["Number"]
        else:
            skills_num_dict[row["Skill"]] += row["Number"]
    return skills_num_dict


def create_output_dataframe_missing_stem_skills(set_diff, is_save=True):
    """
    Function to create and save a dataframe with columns as the [Missing stem, corresponding skills]

    Parameters:
    - the difference set of stememd skill

    Returns:
    None
    """
    difference_skills_in_external_not_in_internal = pd.DataFrame({"stemmed_skill": list(set_diff)})

    difference_skills_in_external_not_in_internal["skills"] = difference_skills_in_external_not_in_internal[
        "stemmed_skill"
    ].map(skills_dict)

    if is_save:
        difference_skills_in_external_not_in_internal.to_csv(
            "difference_skills_in_external_not_in_internal.csv", index=False
        )


def create_output_dataframe_number_missing_skills(skills_num_dict, set_diff, is_save=True):
    """
    Function to create and save a dataframe with columns as the [Missing Skill, Number]

    Parameters:
    - the difference set of stemmed skill
    - the dictionary of {skills: Number}

    Returns:
    None
    """

    missing_skills_dict = {}
    for ele in set_diff:
        for e in skills_dict[ele]:
            missing_skills_dict[e] = skills_num_dict[e]
    number_of_skills_missing = pd.DataFrame(list(missing_skills_dict.items()), columns=["Skill", "Number"])
    if is_save:
        number_of_skills_missing.to_csv("Number_Missing_Skills.csv", index=False)


def main(language):
    # Get the dataframe of internal and external skills
    df_bucket_internal_skills = get_internal_skills_s3_bucket("vs-skills-engine-us-west-2")
    df_external_skills = get_external_dataframe(language)
    # Set the threshold
    threshold = 10
    df_external_skills_reduced = df_external_skills[df_external_skills["Number"] >= threshold]
    skills_num_dict = get_num(df_external_skills_reduced)

    # Get the set of external and internal skills
    set_stemmed_external_skills = set(map(get_stem, list(df_external_skills_reduced["Skill"])))
    set_stemmed_internal_bucket_skills = set(filter(check_english, list(df_bucket_internal_skills["stemmed_skill"])))
    # Take a set diference {external skills}-{internal_skills}
    set_diff = set(set_stemmed_external_skills).difference(set_stemmed_internal_bucket_skills)

    # Save the output in csv format
    create_output_dataframe_missing_stem_skills(set_diff)
    create_output_dataframe_number_missing_skills(skills_num_dict, set_diff)


if __name__ == "__main__":
    nlp_init.init()  # Call this fucntion to avoid any errors further
    parser = argparse.ArgumentParser()
    parser.add_argument(
        "-l",
        "--language_code",
        type=str,
        default="en",
        help='Language code to process skills for, default is "en"',
    )
    args = parser.parse_args()
    main(args.language_code)
