function log_level_lower_case(tag, timestamp, record)
    error_level = record["level"]
    if (error_level ~= nil and error_level ~= '') then
        new_record = record
        new_record["level"] = string.lower(error_level)
        return 2, timestamp, new_record
    end

end

function log_level_by_http_code(tag, timestamp, record)
    http_code = record["code"]
    if (http_code == nil and http_code == '' or error_level ~= nil and error_level ~= '') then
        return 0,0,0
    end
    new_record = record
    if (string.match(http_code, "^5%d%d$")) then
        new_record["level"] = 'error'
    elseif (string.match(http_code, "^4%d%d$")) then
        new_record["level"] = 'warning'
    else
        new_record["level"] = 'info'
    end
    return 2, timestamp, new_record
end