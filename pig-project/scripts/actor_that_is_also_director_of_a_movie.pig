-- Load records from the title-and-actor.txt file (tab separated).
file1 = LOAD '../data/director-and-title.txt' AS (director: chararray, title: chararray, year: int);
-- Load records from the title-and-actor.txt file (tab separated).
file2 = LOAD '../data/title-and-actor.txt' AS (title: chararray, actor: chararray, year_of_birth: int,role: chararray);

-- Cogroup file1 and file2
cogrouped = COGROUP file1 BY director, file2 BY actor;

-- Generate a subset of the columns in the output.
cogrouped_subset = FOREACH cogrouped GENERATE group, file2.(title), file1.(title);

-- Filter out actors that didn't direct movies.
cogrouped_subset_filtered = FILTER cogrouped_subset BY NOT (IsEmpty($1) OR IsEmpty($2));

-- Output the result.
STORE cogrouped_subset_filtered INTO '../results/actor_that_is_also_director_of_a_movie';
