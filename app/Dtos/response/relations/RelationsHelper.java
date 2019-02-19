package com.melardev.spring.twitterapi.dtos.response.relations;

import com.melardev.spring.twitterapi.dtos.response.users.UserOnlyUsernameDto;
import com.melardev.spring.twitterapi.models.User;

import java.util.ArrayList;
import java.util.Collection;
import java.util.List;

public class RelationsHelper {

    public static List<String> getUsernames(Collection<User> users) {
        List<String> usersList = new ArrayList<>();
        users.forEach(u -> usersList.add(UserOnlyUsernameDto.buildAsString(u)));
        return usersList;
    }
}
