meal = float(input("Enter cost of meal: "))
tax = 0.0925
tip = 0.15
meal = meal + meal * tax
total = meal + meal * tip
print("$%.2f total" % total)
diners = int(input("Enter number of diners: "))
share = total / diners
print("Each diners share is $%.2f" % share)
