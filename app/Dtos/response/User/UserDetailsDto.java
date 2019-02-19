package com.melardev.spring.twitterapi.dtos.response.users;

import com.melardev.spring.twitterapi.dtos.response.roles.partials.RoleOnlyNameDto;
import com.melardev.spring.twitterapi.models.User;
import org.springframework.security.core.GrantedAuthority;

import java.util.ArrayList;
import java.util.Collection;
import java.util.stream.Collectors;

public class UserDetailsDto {
    private final String username;
    private final String email;


    // private final Collection<RoleOnlyNameDto> roles;
    private final Collection<String> roles;

    public UserDetailsDto(String username, String email, Collection<String> roles) {

        this.username = username;
        this.email = email;
        // this.roles = roles;
        this.roles = roles;
    }

    public static UserDetailsDto build(User user) {
        Collection<String> roleOnlyNameDtos = new ArrayList<>();
        for (GrantedAuthority role : user.getAuthorities())
            roleOnlyNameDtos.add(RoleOnlyNameDto.buildAsString(role));

        return new UserDetailsDto(user.getUsername(), user.getEmail(), roleOnlyNameDtos);
    }

    public String getUsername() {
        return username;
    }

    public String getEmail() {
        return email;
    }

    public Collection<String> getRoles() {
        return roles;
    }
}
